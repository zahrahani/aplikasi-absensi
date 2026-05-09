import 'package:flutter/material.dart';
import 'dart:math' as math;

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Absen Pulang',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF2B1FCC)),
        useMaterial3: true,
        fontFamily: 'Roboto',
      ),
      home: const AbsenPulangScreen(),
    );
  }
}

class AbsenPulangScreen extends StatefulWidget {
  const AbsenPulangScreen({super.key});

  @override
  State<AbsenPulangScreen> createState() => _AbsenPulangScreenState();
}

class _AbsenPulangScreenState extends State<AbsenPulangScreen>
    with SingleTickerProviderStateMixin {
  late AnimationController _scannerController;
  late Animation<double> _scannerAnimation;

  @override
  void initState() {
    super.initState();
    _scannerController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 2),
    )..repeat(reverse: true);

    _scannerAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(parent: _scannerController, curve: Curves.easeInOut),
    );
  }

  @override
  void dispose() {
    _scannerController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF2B1FCC),
      body: SafeArea(
        child: Column(
          children: [
            _buildAppBar(),

            Expanded(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const SizedBox(height: 20),
                  const Text(
                    'Arahkan QR ke kamera',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.w400,
                    ),
                  ),
                  const SizedBox(height: 24),
                  _buildQRScannerBox(),
                ],
              ),
            ),

            _buildBottomCard(),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 12.0),
      child: Stack(
        alignment: Alignment.center,
        children: [
          Align(
            alignment: Alignment.centerLeft,
            child: Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(
                Icons.arrow_back,
                color: Color(0xFF2B1FCC),
                size: 20,
              ),
            ),
          ),
          const Text(
            'Absen Pulang',
            style: TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w700,
              letterSpacing: 0.3,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildQRScannerBox() {
    return Container(
      width: 280,
      height: 280,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.3),
            blurRadius: 20,
            spreadRadius: 2,
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(12),
        child: Stack(
          children: [
            Container(
              width: 280,
              height: 280,
              color: const Color(0xFF3A3A4A),
              child: CustomPaint(
                painter: QRCodePainter(),
                size: const Size(280, 280),
              ),
            ),

            AnimatedBuilder(
              animation: _scannerAnimation,
              builder: (context, child) {
                return Positioned(
                  top: _scannerAnimation.value * 240,
                  left: 0,
                  right: 0,
                  child: Container(
                    height: 2.5,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        colors: [
                          Colors.transparent,
                          const Color(0xFF9B8FFF).withOpacity(0.8),
                          const Color(0xFFB8AFFF),
                          const Color(0xFF9B8FFF).withOpacity(0.8),
                          Colors.transparent,
                        ],
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFFB8AFFF).withOpacity(0.6),
                          blurRadius: 8,
                          spreadRadius: 2,
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),

            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              height: 140,
              child: Container(
                decoration: BoxDecoration(
                  color: const Color(0xFFE8E6FF).withOpacity(0.15),
                ),
              ),
            ),

            ..._buildCornerMarkers(),
          ],
        ),
      ),
    );
  }

  List<Widget> _buildCornerMarkers() {
    const double markerSize = 24.0;
    const double markerThickness = 4.0;
    const Color markerColor = Colors.white;

    return [
      Positioned(
        top: 12,
        left: 12,
        child: _buildCornerWidget(markerSize, markerThickness, markerColor,
            isTop: true, isLeft: true),
      ),
      Positioned(
        top: 12,
        right: 12,
        child: _buildCornerWidget(markerSize, markerThickness, markerColor,
            isTop: true, isLeft: false),
      ),
      Positioned(
        bottom: 12,
        left: 12,
        child: _buildCornerWidget(markerSize, markerThickness, markerColor,
            isTop: false, isLeft: true),
      ),
      Positioned(
        bottom: 12,
        right: 12,
        child: _buildCornerWidget(markerSize, markerThickness, markerColor,
            isTop: false, isLeft: false),
      ),
    ];
  }

  Widget _buildCornerWidget(
      double size, double thickness, Color color,
      {required bool isTop, required bool isLeft}) {
    return SizedBox(
      width: size,
      height: size,
      child: CustomPaint(
        painter: CornerPainter(
          color: color,
          thickness: thickness,
          isTop: isTop,
          isLeft: isLeft,
        ),
      ),
    );
  }

  Widget _buildBottomCard() {
    return Container(
      margin: EdgeInsets.zero,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(28),
          topRight: Radius.circular(28),
        ),
      ),
      padding: const EdgeInsets.fromLTRB(24, 28, 24, 32),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(
                color: const Color(0xFFE5E5E5),
                width: 1.5,
              ),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.04),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildInfoRow('Berlaku', ': 25 Februari 2026'),
                const SizedBox(height: 10),
                _buildInfoRow('Jam Aktif', ': 16.00 - 17.00'),
              ],
            ),
          ),

          const SizedBox(height: 16),

          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(
                color: const Color(0xFFE5E5E5),
                width: 1.5,
              ),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.04),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: _buildInfoRow('Status', ': Menunggu ...'),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 90,
          child: Text(
            label,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w500,
              color: Color(0xFF1A1A2E),
            ),
          ),
        ),
        Expanded(
          child: Text(
            value,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w500,
              color: Color(0xFF1A1A2E),
            ),
          ),
        ),
      ],
    );
  }
}

class QRCodePainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0xFF1A1A1A)
      ..style = PaintingStyle.fill;

    final bgPaint = Paint()
      ..color = const Color(0xFF4A4A5A)
      ..style = PaintingStyle.fill;

    canvas.drawRect(Rect.fromLTWH(0, 0, size.width, size.height), bgPaint);

    final double cellSize = size.width / 14;

    final List<List<int>> qrPattern = [
      [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      [0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 0],
      [0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0],
      [0, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0],
      [0, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0],
      [0, 1, 0, 0, 0, 0, 1, 0, 1, 1, 0, 0, 1, 0],
      [0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 0],
      [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      [0, 1, 0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0],
      [0, 0, 1, 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0],
      [0, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 0, 1, 0],
      [0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0],
      [0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 1, 1, 1, 0],
      [0, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 0, 1, 0],
    ];

    for (int row = 0; row < qrPattern.length; row++) {
      for (int col = 0; col < qrPattern[row].length; col++) {
        if (qrPattern[row][col] == 1) {
          canvas.drawRect(
            Rect.fromLTWH(
              col * cellSize + 4,
              row * cellSize + 4,
              cellSize - 1,
              cellSize - 1,
            ),
            paint,
          );
        }
      }
    }

    // Draw the 3 finder patterns (corners with inner squares)
    _drawFinderPattern(canvas, 4.0, 4.0, cellSize * 6, paint);
    _drawFinderPattern(canvas, size.width - cellSize * 7 + 4, 4.0, cellSize * 6, paint);
    _drawFinderPattern(canvas, 4.0, size.height - cellSize * 7 + 4, cellSize * 6, paint);
  }

  void _drawFinderPattern(
      Canvas canvas, double x, double y, double size, Paint paint) {
    final outerPaint = Paint()
      ..color = const Color(0xFF1A1A1A)
      ..style = PaintingStyle.stroke
      ..strokeWidth = size / 6;

    final innerPaint = Paint()
      ..color = const Color(0xFF1A1A1A)
      ..style = PaintingStyle.fill;

    // Outer square
    canvas.drawRect(
      Rect.fromLTWH(x, y, size, size),
      outerPaint,
    );

    // Inner filled square
    final double innerSize = size * 0.4;
    final double innerOffset = (size - innerSize) / 2;
    canvas.drawRect(
      Rect.fromLTWH(x + innerOffset, y + innerOffset, innerSize, innerSize),
      innerPaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

class CornerPainter extends CustomPainter {
  final Color color;
  final double thickness;
  final bool isTop;
  final bool isLeft;

  const CornerPainter({
    required this.color,
    required this.thickness,
    required this.isTop,
    required this.isLeft,
  });

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeWidth = thickness
      ..strokeCap = StrokeCap.square;

    final path = Path();

    if (isTop && isLeft) {
      path.moveTo(0, size.height);
      path.lineTo(0, 0);
      path.lineTo(size.width, 0);
    } else if (isTop && !isLeft) {
      path.moveTo(0, 0);
      path.lineTo(size.width, 0);
      path.lineTo(size.width, size.height);
    } else if (!isTop && isLeft) {
      path.moveTo(0, 0);
      path.lineTo(0, size.height);
      path.lineTo(size.width, size.height);
    } else {
      path.moveTo(0, size.height);
      path.lineTo(size.width, size.height);
      path.lineTo(size.width, 0);
    }

    canvas.drawPath(path, paint);
  }

  @override
  bool shouldRepaint(covariant CornerPainter oldDelegate) =>
      color != oldDelegate.color ||
      thickness != oldDelegate.thickness ||
      isTop != oldDelegate.isTop ||
      isLeft != oldDelegate.isLeft;
}