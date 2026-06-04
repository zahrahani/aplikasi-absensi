import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/detail_pengajuan/DetailPengajuanScreen.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildProgressSection.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildSmallButton.dart';

class buildPengajuanCard extends StatelessWidget {
  final PengajuanItem item;
  final VoidCallback  onCancelled;

  const buildPengajuanCard({
    super.key,
    required this.item,
    required this.onCancelled,
  });

  // ── Warna status ──────────────────────────────────────────
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

  IconData get _statusIcon {
    switch (item.status) {
      case PengajuanStatus.menunggu:  return Icons.access_time_rounded;
      case PengajuanStatus.disetujui: return Icons.check_circle_outline_rounded;
      case PengajuanStatus.ditolak:   return Icons.cancel_outlined;
    }
  }

  String get _statusLabel {
    switch (item.status) {
      case PengajuanStatus.menunggu:  return 'Menunggu';
      case PengajuanStatus.disetujui: return 'Disetujui';
      case PengajuanStatus.ditolak:   return 'Ditolak';
    }
  }

  // ── Icon dari iconName database ───────────────────────────
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

  // ── Warna dari colorHex database ──────────────────────────
  Color _getColor(String namaJenis) {
  switch (namaJenis) {
    case 'Hadir': return AppColors.successColor;
    case 'Telat': return AppColors.purpleColor;
    case 'Sakit': return AppColors.secondaryColor;
    case 'Izin'  : return AppColors.primaryPurple;
    default     : return Colors.grey;
  }
}

  @override
  Widget build(BuildContext context) {
    final bool  canCancel  = item.status == PengajuanStatus.menunggu;
    final Color iconColor = _getColor(item.namaJenis);
    final Color iconBg     = iconColor.withOpacity(0.10);

    return Container(
      decoration: BoxDecoration(
        color       : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border      : Border.all(color: const Color(0xFFE8E8E8)),
        boxShadow   : [
          BoxShadow(
            color     : Colors.black.withOpacity(0.04),
            blurRadius: 8,
            offset    : const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [

          // ── Header ────────────────────────────────────────
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 14, 14, 10),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [

                // Icon jenis
                Container(
                  width : 44,
                  height: 44,
                  decoration: BoxDecoration(
                    color       : iconBg,
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Icon(
                    _getIcon(item.iconName),
                    color: iconColor,
                    size : 22,
                  ),
                ),
                const SizedBox(width: 12),

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
                              fontSize  : 14,
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
                      const SizedBox(height: 3),
                      Text(
                        '${item.tanggalMulai} – ${item.tanggalSelesai}',
                        style: const TextStyle(
                          fontSize: 12,
                          color   : Color(0xFF888888),
                        ),
                      ),
                    ],
                  ),
                ),

                // Badge status
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10, vertical: 4,
                  ),
                  decoration: BoxDecoration(
                    color       : _statusBg,
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(_statusIcon, size: 12, color: _statusColor),
                      const SizedBox(width: 4),
                      Text(
                        _statusLabel,
                        style: TextStyle(
                          color     : _statusColor,
                          fontSize  : 11,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // ── Progress ──────────────────────────────────────
          buildProgressSection(steps: item.steps, status: item.status),

          // ── Footer ────────────────────────────────────────
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 10, 14, 14),
            child: Column(
              children: [

                // Tanggal
                Row(
                  children: [
                    const Icon(
                      Icons.calendar_today_outlined,
                      size : 13,
                      color: Color(0xFF888888),
                    ),
                    const SizedBox(width: 5),
                    Text(
                      'Diajukan ${item.submittedDate}',
                      style: const TextStyle(
                        fontSize: 12,
                        color   : Color(0xFF888888),
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 12),

                // Tombol
                Align(
                  alignment: Alignment.centerRight,
                  child: Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [

                      if (canCancel)
                        buildSmallButton(
                          label      : 'Batalkan',
                          textColor  : AppColors.dangerColor,
                          borderColor: const Color(0xFFFFCDD2),
                          bgColor    : const Color(0xFFFFF5F5),
                          onPressed  : () => _confirmCancel(context),
                        ),

                      buildSmallButton(
                        label      : 'Detail',
                        textColor  : AppColors.primaryPurple,
                        borderColor: const Color(0xFFBBBBDD),
                        bgColor    : Colors.white,
                        onPressed  : () => _openDetail(context),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _confirmCancel(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
        title: const Text(
          'Batalkan Pengajuan',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
        ),
        content: Text(
          'Apakah Anda yakin ingin membatalkan pengajuan ${item.namaJenis} ini?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text(
              'Tidak',
              style: TextStyle(color: Color(0xFF888888)),
            ),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.dangerColor,
              foregroundColor: Colors.white,
              elevation      : 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
            onPressed: () {
              Navigator.pop(context);
              onCancelled(); // ← panggil callback ke screen
            },
            child: const Text('Ya, Batalkan'),
          ),
        ],
      ),
    );
  }

  void _openDetail(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => DetailPengajuanScreen(item: item),
      ),
    );
  }
}