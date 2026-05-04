import 'package:flutter/material.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Form Izin Sakit',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF2B2BCC)),
        useMaterial3: true,
      ),
      home: const FormIzinSakitPage(),
    );
  }
}

class FormIzinSakitPage extends StatefulWidget {
  const FormIzinSakitPage({super.key});

  @override
  State<FormIzinSakitPage> createState() => _FormIzinSakitPageState();
}

class _FormIzinSakitPageState extends State<FormIzinSakitPage> {
  DateTime? _tanggalMulai;
  DateTime? _tanggalSelesai;
  final TextEditingController _alasanController = TextEditingController();
  String? _namaFile;

  Future<void> _pickDate(BuildContext context, bool isMulai) async {
    final now = DateTime.now();
    final picked = await showDatePicker(
      context: context,
      initialDate: now,
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: Color(0xFF2B2BCC),
            ),
          ),
          child: child!,
        );
      },
    );
    if (picked != null) {
      setState(() {
        if (isMulai) {
          _tanggalMulai = picked;
        } else {
          _tanggalSelesai = picked;
        }
      });
    }
  }

  String _formatDate(DateTime? date) {
    if (date == null) return 'MM/DD/YYYY';
    return '${date.month.toString().padLeft(2, '0')}/${date.day.toString().padLeft(2, '0')}/${date.year}';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF2B2BCC),
      body: SafeArea(
        child: Column(
          children: [
            // ── AppBar ─────────────────────────────────────────
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              child: Stack(
                alignment: Alignment.center,
                children: [
                  Align(
                    alignment: Alignment.centerLeft,
                    child: Container(
                      width: 36,
                      height: 36,
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.18),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Icon(
                        Icons.arrow_back,
                        color: Colors.white,
                        size: 20,
                      ),
                    ),
                  ),
                  const Text(
                    'Form Izin Sakit',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 10),

            // ── Scrollable content ──────────────────────────────
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Column(
                  children: [
                    // 1. Profile card
                    _FormCard(
                      child: Row(
                        children: [
                          Container(
                            width: 52,
                            height: 52,
                            decoration: BoxDecoration(
                              color: const Color(0xFFEEEEEE),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Icon(
                              Icons.person_outline,
                              size: 32,
                              color: Color(0xFF888888),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: const [
                              Text(
                                'Halo, John',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF1A1A1A),
                                ),
                              ),
                              SizedBox(height: 4),
                              Text(
                                'Junior Developer  |  CV. NAFIHAKA Creative',
                                style: TextStyle(
                                  fontSize: 12,
                                  color: Color(0xFF555555),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),

                    const SizedBox(height: 16),

                    // 2. Tanggal Mulai Sakit
                    _FormCard(
                      onTap: () => _pickDate(context, true),
                      child: Row(
                        children: [
                          _IconBox(
                            child: CustomPaint(
                              size: const Size(28, 28),
                              painter: _CalendarPainter(
                                  color: const Color(0xFF2B2BCC)),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Tanggal Mulai Sakit',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF1A1A1A),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                _formatDate(_tanggalMulai),
                                style: const TextStyle(
                                  fontSize: 13,
                                  color: Color(0xFF888888),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),

                    const SizedBox(height: 16),

                    // 3. Tanggal Selesai
                    _FormCard(
                      onTap: () => _pickDate(context, false),
                      child: Row(
                        children: [
                          _IconBox(
                            child: CustomPaint(
                              size: const Size(28, 28),
                              painter: _CalendarPainter(
                                  color: const Color(0xFF2B2BCC)),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Tanggal Selesai',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF1A1A1A),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                _formatDate(_tanggalSelesai),
                                style: const TextStyle(
                                  fontSize: 13,
                                  color: Color(0xFF888888),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),

                    const SizedBox(height: 16),

                    // 4. Alasan Perizinan
                    _FormCard(
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _IconBox(
                            child: CustomPaint(
                              size: const Size(28, 28),
                              painter: _NotePainter(),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'Alasan Perizinan',
                                  style: TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.w700,
                                    color: Color(0xFF1A1A1A),
                                  ),
                                ),
                                const SizedBox(height: 4),
                                TextField(
                                  controller: _alasanController,
                                  style: const TextStyle(
                                    fontSize: 13,
                                    color: Color(0xFF555555),
                                  ),
                                  maxLines: 2,
                                  decoration: const InputDecoration(
                                    hintText: 'Keterangan .......',
                                    hintStyle: TextStyle(
                                      fontSize: 13,
                                      color: Color(0xFF888888),
                                    ),
                                    border: InputBorder.none,
                                    isDense: true,
                                    contentPadding: EdgeInsets.zero,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),

                    const SizedBox(height: 16),

                    // 5. Upload Surat Dokter
                    _FormCard(
                      onTap: () {
                        setState(() {
                          _namaFile = 'surat_dokter.pdf';
                        });
                      },
                      child: Row(
                        children: [
                          _IconBox(
                            child: CustomPaint(
                              size: const Size(30, 26),
                              painter: _FolderPainter(),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Upload Surat Dokter',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF1A1A1A),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                _namaFile ?? 'Pilih File / Foto',
                                style: const TextStyle(
                                  fontSize: 13,
                                  color: Color(0xFF888888),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),

                    const SizedBox(height: 32),
                  ],
                ),
              ),
            ),

            // ── Bottom Button ───────────────────────────────────
            Container(
              color: const Color(0xFF2B2BCC),
              padding: const EdgeInsets.fromLTRB(20, 8, 20, 20),
              child: SizedBox(
                width: double.infinity,
                height: 54,
                child: ElevatedButton(
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.white,
                    foregroundColor: const Color(0xFF1A1A1A),
                    elevation: 0,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(14),
                    ),
                  ),
                  child: const Text(
                    'Kirim Pengajuan',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: Color(0xFF1A1A1A),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// Reusable white card wrapper
// ─────────────────────────────────────────────────────────────
class _FormCard extends StatelessWidget {
  final Widget child;
  final VoidCallback? onTap;

  const _FormCard({required this.child, this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
        ),
        child: child,
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// Square icon container (light grey bg)
// ─────────────────────────────────────────────────────────────
class _IconBox extends StatelessWidget {
  final Widget child;

  const _IconBox({required this.child});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 52,
      height: 52,
      decoration: BoxDecoration(
        color: const Color(0xFFF0F0F0),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Center(child: child),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// Calendar painter
// ─────────────────────────────────────────────────────────────
class _CalendarPainter extends CustomPainter {
  final Color color;
  _CalendarPainter({required this.color});

  @override
  void paint(Canvas canvas, Size size) {
    final strokePaint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.8
      ..strokeCap = StrokeCap.round;

    final fillPaint = Paint()
      ..color = color
      ..style = PaintingStyle.fill;

    final w = size.width;
    final h = size.height;

    // Outer rounded rect
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(1, 3, w - 2, h - 4),
        const Radius.circular(3),
      ),
      strokePaint,
    );

    // Header divider
    canvas.drawLine(Offset(1, 9), Offset(w - 1, 9), strokePaint);

    // Hook pins
    canvas.drawLine(Offset(7, 1), Offset(7, 6), strokePaint);
    canvas.drawLine(Offset(w - 7, 1), Offset(w - 7, 6), strokePaint);

    // 3x3 dot grid
    const dotR = 1.6;
    final sx = 5.0;
    final sy = 14.0;
    final gx = (w - 10) / 2;
    final gy = (h - 17) / 2;

    for (int r = 0; r < 3; r++) {
      for (int c = 0; c < 3; c++) {
        canvas.drawCircle(
          Offset(sx + c * gx, sy + r * gy),
          dotR,
          fillPaint,
        );
      }
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

// ─────────────────────────────────────────────────────────────
// Note + pencil painter
// ─────────────────────────────────────────────────────────────
class _NotePainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;

    final bluePaint = Paint()
      ..color = const Color(0xFF2B2BCC)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.8
      ..strokeCap = StrokeCap.round;

    // Note outline
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(1, 1, w - 2, h - 2),
        const Radius.circular(3),
      ),
      bluePaint,
    );

    // Lines on note
    canvas.drawLine(Offset(5, h * 0.32), Offset(w - 5, h * 0.32), bluePaint);
    canvas.drawLine(Offset(5, h * 0.50), Offset(w - 5, h * 0.50), bluePaint);
    canvas.drawLine(Offset(5, h * 0.68), Offset(w * 0.55, h * 0.68), bluePaint);

    // Pencil body (orange fill)
    final pencilFill = Paint()
      ..color = const Color(0xFFFF8C00)
      ..style = PaintingStyle.fill;

    final pencilPath = Path();
    pencilPath.moveTo(w * 0.52, h * 0.62);
    pencilPath.lineTo(w * 0.82, h * 0.32);
    pencilPath.lineTo(w * 0.90, h * 0.40);
    pencilPath.lineTo(w * 0.60, h * 0.70);
    pencilPath.close();
    canvas.drawPath(pencilPath, pencilFill);

    // Pencil tip
    final tipFill = Paint()
      ..color = const Color(0xFFFFC34D)
      ..style = PaintingStyle.fill;

    final tipPath = Path();
    tipPath.moveTo(w * 0.52, h * 0.62);
    tipPath.lineTo(w * 0.60, h * 0.70);
    tipPath.lineTo(w * 0.50, h * 0.75);
    tipPath.close();
    canvas.drawPath(tipPath, tipFill);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

// ─────────────────────────────────────────────────────────────
// Folder painter (yellow)
// ─────────────────────────────────────────────────────────────
class _FolderPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;

    final tabPaint = Paint()
      ..color = const Color(0xFFFFCC44)
      ..style = PaintingStyle.fill;

    final bodyPaint = Paint()
      ..color = const Color(0xFFFFB300)
      ..style = PaintingStyle.fill;

    // Tab flap
    final tabPath = Path();
    tabPath.moveTo(0, h * 0.38);
    tabPath.lineTo(0, h * 0.20);
    tabPath.quadraticBezierTo(0, h * 0.10, w * 0.09, h * 0.10);
    tabPath.lineTo(w * 0.38, h * 0.10);
    tabPath.quadraticBezierTo(w * 0.46, h * 0.10, w * 0.48, h * 0.24);
    tabPath.lineTo(w * 0.50, h * 0.38);
    tabPath.close();
    canvas.drawPath(tabPath, tabPaint);

    // Main folder body
    final bodyPath = Path();
    bodyPath.moveTo(0, h * 0.38);
    bodyPath.lineTo(w, h * 0.38);
    bodyPath.lineTo(w, h * 0.90);
    bodyPath.quadraticBezierTo(w, h, w * 0.90, h);
    bodyPath.lineTo(w * 0.10, h);
    bodyPath.quadraticBezierTo(0, h, 0, h * 0.90);
    bodyPath.close();
    canvas.drawPath(bodyPath, bodyPaint);

    // Highlight line on folder
    final shinePaint = Paint()
      ..color = Colors.white.withOpacity(0.30)
      ..strokeWidth = 1.8
      ..strokeCap = StrokeCap.round
      ..style = PaintingStyle.stroke;
    canvas.drawLine(
      Offset(w * 0.14, h * 0.58),
      Offset(w * 0.52, h * 0.58),
      shinePaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}