import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/scan_qr/widgets_for_scan_qr/buildBatalButton.dart';
import 'package:presensi/features/presentation/auth/scan_qr/widgets_for_scan_qr/buildScannerFrame.dart';

// ScanQRScreen menggunakan StatefulWidget
class ScanQRScreen extends ConsumerStatefulWidget {
  const ScanQRScreen({super.key});

  @override
  ConsumerState<ScanQRScreen> createState() => _ScanQRScreenState();
}

class _ScanQRScreenState extends ConsumerState<ScanQRScreen>
    with SingleTickerProviderStateMixin {

  // Animasi garis scanner yang bergerak naik-turun
  late AnimationController _animController;
  late Animation<double> _scanLineAnim;

  @override
  void initState() {
    super.initState();
    // Controller animasi: durasi 2 detik, berulang bolak-balik
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 2),
    )..repeat(reverse: true);

    // Garis bergerak dari atas (0.0) ke bawah (1.0) area scanner
    _scanLineAnim = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(parent: _animController, curve: Curves.easeInOut),
    );
  }

  @override
  void dispose() {
    _animController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    // Ambil ukuran layar untuk responsivitas
    final double screenWidth = MediaQuery.of(context).size.width;
    final double screenHeight = MediaQuery.of(context).size.height;

    // Ukuran kotak scanner: 70% lebar layar, maksimal 280
    final double scanBoxSize = (screenWidth * 0.70).clamp(220.0, 280.0);

    return Scaffold(
      backgroundColor: AppColors.primaryPurple,
      body: SafeArea(
        child: Column(
          children: [
            // Judul "Scan QR Code"
            Padding(
              padding: EdgeInsets.only(
                top: screenHeight * 0.05,
                bottom: 8,
              ),
              child: const Text(
                'Scan QR Code Presensi',
                style: TextStyle(
                  fontFamily: 'Roboto',
                  color: Colors.white,
                  fontSize: 24,
                  fontWeight: FontWeight.w700,
                  letterSpacing: 0.3,
                ),
              ),
            ),

            // Area scanner di tengah layar
            Expanded(
              child: Center(
                child: buildScannerFrame(
                  size: scanBoxSize,
                  scanLineAnim: _scanLineAnim,
                  context: context,
                  ref: ref             
                ),
              ),
            ),

            // Tombol Batal di bawah
            Padding(
              padding: EdgeInsets.only(
                bottom: screenHeight * 0.06,
              ),
              child: buildBatalButton(context),
            ),
          ],
        ),
      ),
    );
  }

 
 

  
}