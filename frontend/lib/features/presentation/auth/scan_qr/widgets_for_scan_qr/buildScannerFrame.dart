import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:presensi/features/presentation/auth/scan_qr/ScanQRController.dart';
import 'package:presensi/features/presentation/auth/scan_qr/widgets_for_scan_qr/buildCorner.dart';
import 'package:presensi/features/widgets/showAlert.dart';

Widget buildScannerFrame({
  required double size,
  required Animation<double> scanLineAnim,
  required BuildContext context,
  required WidgetRef ref, // ← tambah ini
}) {
  const double cornerThickness = 4.0;
  const double cornerLength    = 28.0;
  const double cornerRadius    = 4.0;
  bool sudahDeteksi = false;

  return SizedBox(
    width: size,
    height: size,
    child: Stack(
      children: [

        // ── Area kamera scanner ───────────────────────────────
        SizedBox(
          width: size,
          height: size,
          child: ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: MobileScanner(
              onDetect: (capture) async {
                if (sudahDeteksi) return;

                final String? tokenQr = capture.barcodes.first.rawValue;
                if (tokenQr == null) return;

                sudahDeteksi = true;

                // Panggil controller — auth + GPS + kirim server
                final result = await ScanQRController.prosesAbsen(tokenQr, ref);

                showAlert(
                  context: context,
                  message: result['messages'],
                  alertStatus: result['success'] ? 'success' : 'danger',
                );
                // tunggu 5 detik
                Future.delayed(
                  const Duration(seconds: 5),
                  () {
                    // pindah halaman dashboard
                    Navigator.pushReplacementNamed(
                      context,
                      '/main',
                    );

                  },
                );
                // sudahDeteksi = false;
              },
            ),
          ),
        ),

        // ── Garis scan animasi ────────────────────────────────
        AnimatedBuilder(
          animation: scanLineAnim,
          builder: (context, child) {
            return Positioned(
              top: scanLineAnim.value * (size - 2),
              left: 0,
              right: 0,
              child: Container(
                height: 2,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      Colors.transparent,
                      Colors.white.withOpacity(0.8),
                      Colors.white,
                      Colors.white.withOpacity(0.8),
                      Colors.transparent,
                    ],
                  ),
                ),
              ),
            );
          },
        ),

        // ── 4 Sudut scanner ───────────────────────────────────
        Positioned(top: 0, left: 0,
          child: buildCorner(cornerLength, cornerThickness, cornerRadius,
            isTop: true, isLeft: true)),
        Positioned(top: 0, right: 0,
          child: buildCorner(cornerLength, cornerThickness, cornerRadius,
            isTop: true, isLeft: false)),
        Positioned(bottom: 0, left: 0,
          child: buildCorner(cornerLength, cornerThickness, cornerRadius,
            isTop: false, isLeft: true)),
        Positioned(bottom: 0, right: 0,
          child: buildCorner(cornerLength, cornerThickness, cornerRadius,
            isTop: false, isLeft: false)),
      ],
    ),
  );
}