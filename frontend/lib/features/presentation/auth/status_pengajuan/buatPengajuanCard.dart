import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/statusPengajuanController.dart';
import 'package:presensi/features/widgets/InputField.dart';
import 'package:presensi/features/widgets/showAlert.dart';

class BuatPengajuanSheet extends StatefulWidget {
  final WidgetRef ref;
  final VoidCallback onSubmitted;

  const BuatPengajuanSheet({
    super.key,
    required this.ref,
    required this.onSubmitted,
  });

  @override
  State<BuatPengajuanSheet> createState() => _BuatPengajuanSheetState();
}

class _BuatPengajuanSheetState extends State<BuatPengajuanSheet> {
  final TextEditingController _tglMulai   = TextEditingController();
  final TextEditingController _tglSelesai = TextEditingController();
  final TextEditingController _catatan    = TextEditingController();
  String? _jenisError;
  String? _tglMulaiError;
  String? _tglSelesaiError;
  String? _catatanError;

  bool    _isLoading = false;
  bool    _isUrgent  = false;
  String? _jenisId;

  // Jenis yang bisa diajukan sesuai database
  final List<Map<String, dynamic>> _jenisOptions = [
    {'id': 'J03', 'label': 'Sakit',  'icon': Icons.sick_outlined,        'color': const Color(0xFF808080)},
    {'id': 'J04', 'label': 'Cuti',   'icon': Icons.event_outlined,        'color': const Color(0xFFF5A623)},
    {'id': 'J05', 'label': 'WFH',    'icon': Icons.home_work_outlined,    'color': const Color(0xFF1900A7)},
  ];

  @override
  void dispose() {
    _tglMulai.dispose();
    _tglSelesai.dispose();
    _catatan.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
      setState(() {
      _jenisError      = null;
      _tglMulaiError   = null;
      _tglSelesaiError = null;
      _catatanError    = null;
    });

    bool isValid = true;

    if (_jenisId == null) {
      _jenisError = 'Jenis pengajuan wajib dipilih';
      isValid = false;
    }

    if (_tglMulai.text.isEmpty) {
      _tglMulaiError = 'Tanggal mulai wajib diisi';
      isValid = false;
    }

    if (_tglSelesai.text.isEmpty) {
      _tglSelesaiError = 'Tanggal selesai wajib diisi';
      isValid = false;
    }

    if (!isValid) {
      setState(() {});
      return;
    }

    setState(() => _isLoading = true);

    final result = await StatusPengajuanController.buatPengajuan(
      ref           : widget.ref,
      jenisId       : _jenisId!,
      tanggalMulai  : _tglMulai.text,
      tanggalSelesai: _tglSelesai.text.isEmpty ? null : _tglSelesai.text,
      alasan        : _catatan.text.isEmpty    ? null : _catatan.text,
      isUrgent      : _isUrgent,
    );

    if (!mounted) return;
    setState(() => _isLoading = false);
    Navigator.pop(context);
    if (result['success']) {
      showAlert(
        context    : context,
        message    : result['messages'],
        alertStatus: 'success',
      );
      widget.onSubmitted();
    } else {
      showAlert(
        context    : context,
        message    : result['messages'],
        alertStatus: 'danger',
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.85,
      minChildSize    : 0.5,
      maxChildSize    : 0.95,
      builder: (_, scrollCtrl) => Container(
        decoration: const BoxDecoration(
          color        : Colors.white,
          borderRadius : BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: Column(
          children: [
            // ── Handle ────────────────────────────────────
            const SizedBox(height: 10),
            Container(
              width : 40,
              height: 4,
              decoration: BoxDecoration(
                color        : const Color(0xFFDDDDDD),
                borderRadius : BorderRadius.circular(2),
              ),
            ),
            const SizedBox(height: 16),

            // ── Judul ─────────────────────────────────────
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: Text(
                'Buat Pengajuan Baru',
                style: TextStyle(
                  fontSize  : 18,
                  fontWeight: FontWeight.bold,
                  color     : Color(0xFF1A1A2E),
                ),
              ),
            ),
            const SizedBox(height: 4),
            const Text(
              'Isi formulir di bawah ini',
              style: TextStyle(fontSize: 13, color: Color(0xFF888888)),
            ),
            const Divider(height: 24),

            // ── Form ──────────────────────────────────────
            Expanded(
              child: ListView(
                controller: scrollCtrl,
                padding   : const EdgeInsets.fromLTRB(20, 0, 20, 24),
                children  : [

                  // Jenis pengajuan
                  fieldLabel('Jenis Pengajuan'),
                  const SizedBox(height: 10),
                  ..._jenisOptions.map((jenis) {
                    final isSelected = _jenisId == jenis['id'];
                    return GestureDetector(
                      onTap: () => setState(() => _jenisId = jenis['id']),
                      child: Container(
                        margin: const EdgeInsets.only(bottom: 8),
                        padding: const EdgeInsets.symmetric(
                          horizontal: 14, vertical: 12,
                        ),
                        decoration: BoxDecoration(
                          color       : isSelected
                              ? AppColors.primaryPurple.withOpacity(0.06)
                              : const Color(0xFFF8F8F8),
                          borderRadius: BorderRadius.circular(12),
                          border      : Border.all(
                            color: isSelected
                                ? AppColors.primaryPurple
                                : const Color(0xFFE0E0E0),
                            width: isSelected ? 1.5 : 1,
                          ),
                        ),
                        child: Row(
                          children: [
                            Container(
                              padding   : const EdgeInsets.all(8),
                              decoration: BoxDecoration(
                                color       : (jenis['color'] as Color)
                                    .withOpacity(0.12),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Icon(
                                jenis['icon'] as IconData,
                                color: jenis['color'] as Color,
                                size : 18,
                              ),
                            ),
                            const SizedBox(width: 12),
                            Text(
                              jenis['label'] as String,
                              style: TextStyle(
                                fontSize  : 14,
                                fontWeight: FontWeight.w500,
                                color     : isSelected
                                    ? AppColors.primaryPurple
                                    : const Color(0xFF1A1A2E),
                              ),
                            ),
                            const Spacer(),
                            if (isSelected)
                              const Icon(
                                Icons.check_circle_rounded,
                                color: AppColors.primaryPurple,
                                size : 20,
                              ),
                          ],
                        ),
                      ),
                    );
                  }),
                  if (_jenisError != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 6, left: 12),
                      child: Text(
                        _jenisError!,
                        style: const TextStyle(
                          color: Colors.red,
                          fontSize: 12,
                        ),
                      ),
                    ),

                  const SizedBox(height: 16),

                  // Tanggal mulai
                  dateField(
                    ctrl   : _tglMulai,
                    hint   : 'Pilih tanggal mulai',
                    label  : 'Tanggal Mulai *',
                    context: context,
                  ),
                  if (_tglMulaiError != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 6, left: 12),
                    child: Text(
                      _tglMulaiError!,
                      style: const TextStyle(
                        color: Colors.red,
                        fontSize: 12,
                      ),
                    ),
                  ),

                  const SizedBox(height: 16),

                  // Tanggal selesai
                  dateField(
                    ctrl   : _tglSelesai,
                    hint   : 'Pilih tanggal selesai',
                    label  : 'Tanggal Selesai',
                    context: context,
                  ),
                  if (_tglSelesaiError != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 6, left: 12),
                    child: Text(
                      _tglSelesaiError!,
                      style: const TextStyle(
                        color: Colors.red,
                        fontSize: 12,
                      ),
                    ),
                  ),


                  const SizedBox(height: 16),

                  // Catatan / alasan
                  fieldLabel('Catatan / Alasan'),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _catatan,
                    maxLines  : 3,
                    decoration: InputDecoration(
                      hintText : 'Tambahkan keterangan atau alasan...',
                      hintStyle: const TextStyle(
                        color   : Color(0xFFAAAAAA),
                        fontSize: 13,
                      ),
                      filled      : true,
                      fillColor   : const Color(0xFFF8F8F8),
                      border      : OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide  : const BorderSide(color: Color(0xFFE0E0E0)),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide  : const BorderSide(color: Color(0xFFE0E0E0)),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide  : const BorderSide(
                          color: AppColors.primaryPurple,
                          width: 1.5,
                        ),
                      ),
                      contentPadding: const EdgeInsets.all(14),
                    ),
                  ),
                  if (_catatanError != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 6, left: 12),
                    child: Text(
                      _catatanError!,
                      style: const TextStyle(
                        color: Colors.red,
                        fontSize: 12,
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Toggle urgent
                  Container(
                    padding   : const EdgeInsets.symmetric(
                      horizontal: 14, vertical: 12,
                    ),
                    decoration: BoxDecoration(
                      color       : const Color(0xFFF8F8F8),
                      borderRadius: BorderRadius.circular(12),
                      border      : Border.all(color: const Color(0xFFE0E0E0)),
                    ),
                    child: Row(
                      children: [
                        const Icon(
                          Icons.priority_high_rounded,
                          color: Color(0xFFE53E3E),
                          size : 20,
                        ),
                        const SizedBox(width: 10),
                        const Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Tandai sebagai mendesak',
                                style: TextStyle(
                                  fontSize  : 13,
                                  fontWeight: FontWeight.w500,
                                  color     : Color(0xFF1A1A2E),
                                ),
                              ),
                              Text(
                                'Admin akan diprioritaskan untuk meninjau',
                                style: TextStyle(
                                  fontSize: 11,
                                  color   : Color(0xFF888888),
                                ),
                              ),
                            ],
                          ),
                        ),
                        Switch(
                          value          : _isUrgent,
                          onChanged      : (v) => setState(() => _isUrgent = v),
                          activeColor    : const Color(0xFFE53E3E),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 28),

                  // Tombol submit
                  SizedBox(
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primaryPurple,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                        elevation: 0,
                      ),
                      child: _isLoading
                          ? const SizedBox(
                              width : 20,
                              height: 20,
                              child : CircularProgressIndicator(
                                color      : Colors.white,
                                strokeWidth: 2,
                              ),
                            )
                          : const Text(
                              'Ajukan Sekarang',
                              style: TextStyle(
                                fontSize  : 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}