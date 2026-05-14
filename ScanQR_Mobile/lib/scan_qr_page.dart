import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';

// QrScannerPage menggunakan StatefulWidget
class QrScannerPage extends StatefulWidget {
  const QrScannerPage({super.key});

  @override
  State<QrScannerPage> createState() => _QrScannerPageState();
}

class _QrScannerPageState extends State<QrScannerPage>
    with SingleTickerProviderStateMixin {
  static const Color _primaryPurple = Color(0xFF1900A7);

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
      backgroundColor: _primaryPurple,
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
                child: _buildScannerFrame(scanBoxSize),
              ),
            ),

            // Tombol Batal di bawah
            Padding(
              padding: EdgeInsets.only(
                bottom: screenHeight * 0.06,
              ),
              child: _buildBatalButton(),
            ),
          ],
        ),
      ),
    );
  }

  // Widget: Frame scanner QR
  Widget _buildScannerFrame(double size) {
    // Tebal garis sudut scanner
    const double cornerThickness = 4.0;
    // Panjang garis sudut scanner
    const double cornerLength = 28.0;
    // Radius sudut garis agar sedikit rounded
    const double cornerRadius = 4.0;

    return SizedBox(
      width: size,
      height: size,
      child: Stack(
        children: [
          // Background area scan: gelap transparan
          SizedBox(
            width: size,
            height: size,
            child: ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: MobileScanner(
                onDetect: (capture) {
                  final barcode = capture.barcodes.first;
                  final String? code = barcode.rawValue;
                  if (code != null) {
                    print('QR terbaca: $code');
                  }
                },
              ),
            ),
          ),

          // Garis scanner animasi bergerak
          AnimatedBuilder(
            animation: _scanLineAnim,
            builder: (context, child) {
              return Positioned(
                // Posisi garis bergerak dari atas ke bawah area scanner
                top: _scanLineAnim.value * (size - 2),
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

          // 4 Sudut scanner 
          // Sudut KIRI ATAS
          Positioned(
            top: 0,
            left: 0,
            child: _buildCorner(
              cornerLength,
              cornerThickness,
              cornerRadius,
              isTop: true,
              isLeft: true,
            ),
          ),
          // Sudut KANAN ATAS
          Positioned(
            top: 0,
            right: 0,
            child: _buildCorner(
              cornerLength,
              cornerThickness,
              cornerRadius,
              isTop: true,
              isLeft: false,
            ),
          ),
          // Sudut KIRI BAWAH
          Positioned(
            bottom: 0,
            left: 0,
            child: _buildCorner(
              cornerLength,
              cornerThickness,
              cornerRadius,
              isTop: false,
              isLeft: true,
            ),
          ),
          // Sudut KANAN BAWAH
          Positioned(
            bottom: 0,
            right: 0,
            child: _buildCorner(
              cornerLength,
              cornerThickness,
              cornerRadius,
              isTop: false,
              isLeft: false,
            ),
          ),
        ],
      ),
    );
  }

  // Widget: Satu sudut scanner (garis siku L)
  // isTop: true = atas, false = bawah
  // isLeft: true = kiri, false = kanan
  Widget _buildCorner(
    double length,
    double thickness,
    double radius, {
    required bool isTop,
    required bool isLeft,
  }) {
    return SizedBox(
      width: length,
      height: length,
      child: Stack(
        children: [
          // Garis horizontal
          Positioned(
            top: isTop ? 0 : null,
            bottom: isTop ? null : 0,
            left: isLeft ? 0 : null,
            right: isLeft ? null : 0,
            child: Container(
              width: length,
              height: thickness,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(radius),
              ),
            ),
          ),
          // Garis vertikal
          Positioned(
            top: isTop ? 0 : null,
            bottom: isTop ? null : 0,
            left: isLeft ? 0 : null,
            right: isLeft ? null : 0,
            child: Container(
              width: thickness,
              height: length,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(radius),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // Widget: Tombol Batal
  Widget _buildBatalButton() {
    return SizedBox(
      width: 125,
      height: 35,
      child: ElevatedButton(
        onPressed: () {
          // Kembali ke halaman sebelumnya
          Navigator.pop(context);
        },
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.white,
          foregroundColor: const Color(0xFF1900A7),
          elevation: 0,
          shape: const StadiumBorder(), // Bentuk pill/rounded penuh
        ),
        child: const Text(
          'Batal',
          style: TextStyle(
            fontFamily: 'Roboto',
            fontSize: 18,
            fontWeight: FontWeight.w900,
            color: Color(0xFF1900A7),
          ),
        ),
      ),
    );
  }
}