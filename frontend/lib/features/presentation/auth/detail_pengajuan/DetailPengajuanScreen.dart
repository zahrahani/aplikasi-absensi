import 'package:flutter/material.dart' hide StepState;
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/detail_pengajuan/widgets_for_pengajuan_page/builtDetailCard.dart';
import 'package:presensi/features/presentation/auth/detail_pengajuan/widgets_for_pengajuan_page/builtDetailStepRow.dart';
import 'package:presensi/features/presentation/auth/detail_pengajuan/widgets_for_pengajuan_page/builtInfoRow.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';

class DetailPengajuanScreen extends StatelessWidget {
  final PengajuanItem item;
  const DetailPengajuanScreen({super.key, required this.item});

  Color get _statusColor {
    switch (item.status) {
      case PengajuanStatus.menunggu:  return AppColors.warningColor;
      case PengajuanStatus.disetujui: return AppColors.successColor;
      case PengajuanStatus.ditolak:   return AppColors.dangerColor;
    }
  }

  Color get _statusBg {
    switch (item.status) {
      case PengajuanStatus.menunggu:  return const Color(0xFFFFF3E0);
      case PengajuanStatus.disetujui: return const Color(0xFFE8F5E9);
      case PengajuanStatus.ditolak:   return const Color(0xFFFFEBEE);
    }
  }

  String get _statusLabel {
    switch (item.status) {
      case PengajuanStatus.menunggu:  return 'Menunggu';
      case PengajuanStatus.disetujui: return 'Disetujui';
      case PengajuanStatus.ditolak:   return 'Ditolak';
    }
  }

  // ── Sama seperti di buildPengajuanCard ────────────────────
  IconData _getIcon(String iconName) {
    switch (iconName) {
      case 'sick'    : return Icons.sick_outlined;
      case 'event'   : return Icons.event_outlined;
      case 'home'    : return Icons.home_work_outlined;
      case 'schedule': return Icons.schedule_outlined;
      case 'check'   : return Icons.check_circle_outline_rounded;
      default        : return Icons.description_outlined;
    }
  }

  Color _getColor(String namaJenis) {
    switch (namaJenis) {
      case 'Hadir': return AppColors.successColor;
      case 'Telat': return AppColors.purpleColor;
      case 'Sakit': return AppColors.secondaryColor;
      case 'Cuti' : return AppColors.warningColor;
      case 'WFH'  : return AppColors.primaryPurple;
      default     : return Colors.grey;
    }
  }
  @override
  Widget build(BuildContext context) {
    final Color iconColor = _getColor(item.namaJenis);
    final Color iconBg    = iconColor.withOpacity(0.10);

    return Scaffold(
      backgroundColor: AppColors.primaryPurple,
      body: SafeArea(
        child: Column(
          children: [
            // ── AppBar ────────────────────────────────────
            Padding(
              padding: const EdgeInsets.fromLTRB(12, 12, 20, 16),
              child: Row(
                children: [
                  IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(
                      Icons.arrow_back_ios_new_rounded,
                      color: Colors.white,
                      size: 20,
                    ),
                  ),
                  const Expanded(
                    child: Text(
                      'Detail Pengajuan',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color     : Colors.white,
                        fontSize  : 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  const SizedBox(width: 44),
                ],
              ),
            ),

            // ── Body ──────────────────────────────────────
            Expanded(
              child: Container(
                decoration: const BoxDecoration(
                  color       : AppColors.whiteBackground,
                  borderRadius: BorderRadius.only(
                    topLeft : Radius.circular(28),
                    topRight: Radius.circular(28),
                  ),
                ),
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [

                      // ── Title card ────────────────────────
                      Container(
                        padding   : const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color       : Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          boxShadow   : [
                            BoxShadow(
                              color    : Colors.black.withOpacity(0.04),
                              blurRadius: 8,
                            ),
                          ],
                        ),
                        child: Row(
                          children: [
                            // Icon jenis
                            Container(
                              width : 52,
                              height: 52,
                              decoration: BoxDecoration(
                                color       : iconBg,
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Icon(
                                _getIcon(item.iconName),
                                color: iconColor,
                                size : 26,
                              ),
                            ),
                            const SizedBox(width: 14),

                            // Nama jenis + tanggal
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: [
                                      Text(
                                        item.namaJenis,
                                        style: const TextStyle(
                                          fontSize  : 16,
                                          fontWeight: FontWeight.bold,
                                          color     : Color(0xFF1A1A2E),
                                        ),
                                      ),
                                      if (item.isUrgent) ...[
                                        const SizedBox(width: 6),
                                        Container(
                                          padding: const EdgeInsets.symmetric(
                                            horizontal: 6, vertical: 2,
                                          ),
                                          decoration: BoxDecoration(
                                            color       : const Color(0xFFFFEBEE),
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: const Text(
                                            'Mendesak',
                                            style: TextStyle(
                                              fontSize  : 10,
                                              fontWeight: FontWeight.w600,
                                              color     : Color(0xFFE53E3E),
                                            ),
                                          ),
                                        ),
                                      ],
                                    ],
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    '${item.tanggalMulai} – ${item.tanggalSelesai}',
                                    style: const TextStyle(
                                      fontSize: 13,
                                      color   : Color(0xFF888888),
                                    ),
                                  ),
                                ],
                              ),
                            ),

                            // Badge status
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 6,
                              ),
                              decoration: BoxDecoration(
                                color       : _statusBg,
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(
                                _statusLabel,
                                style: TextStyle(
                                  color     : _statusColor,
                                  fontSize  : 12,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 16),

                      // ── Info rows ─────────────────────────
                      builtDetailCard(
                        children: [
                          builtInfoRow(
                            Icons.tag,
                            'ID Pengajuan',
                            '#${item.pengajuanId}',
                          ),
                          _divider(),
                          builtInfoRow(
                            Icons.calendar_today_outlined,
                            'Tanggal Diajukan',
                            item.submittedDate,
                          ),
                          _divider(),
                          builtInfoRow(
                            Icons.calendar_month_outlined,
                            'Tanggal Mulai',
                            item.tanggalMulai,
                          ),
                          _divider(),
                          builtInfoRow(
                            Icons.calendar_month_outlined,
                            'Tanggal Selesai',
                            item.tanggalSelesai,
                          ),
                          _divider(),
                          builtInfoRow(
                            Icons.note_outlined,
                            'Alasan',
                            item.alasan,
                          ),
                          // Catatan admin hanya tampil jika ada
                          if (item.catatanAdmin != '-') ...[
                            _divider(),
                            builtInfoRow(
                              Icons.admin_panel_settings_outlined,
                              'Catatan Admin',
                              item.catatanAdmin,
                            ),
                          ],
                        ],
                      ),
                      const SizedBox(height: 16),

                      // ── Progress persetujuan ──────────────
                      const Text(
                        'PROGRES PERSETUJUAN',
                        style: TextStyle(
                          fontSize    : 11,
                          fontWeight  : FontWeight.bold,
                          color       : Color(0xFF888888),
                          letterSpacing: 0.8,
                        ),
                      ),
                      const SizedBox(height: 10),
                      builtDetailCard(
                        children: List.generate(item.steps.length, (i) {
                          return builtDetailStepRow(
                            step  : item.steps[i],
                            isLast: i == item.steps.length - 1,
                          );
                        }),
                      ),
                      const SizedBox(height: 24),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _divider() => const Divider(
    height   : 1,
    indent   : 16,
    endIndent: 16,
        color: Color(0xFFF0F0F0),
  );
}