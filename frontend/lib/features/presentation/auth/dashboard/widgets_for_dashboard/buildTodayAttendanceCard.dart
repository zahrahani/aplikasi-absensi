// Widget: untuk menampilkan absensi hari ini
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildTodayAttendanceCard({
  required String checkIn,
  required String checkOut,
  required String duration,
  required String checkInStatus,
}) {
  return Container(
    margin: const EdgeInsets.fromLTRB(16, 12, 16, 0),
    padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(16),
      boxShadow: [
        BoxShadow(
          color: Colors.black.withOpacity(0.06),
          blurRadius: 10,
          offset: const Offset(0, 4),
        ),
      ],
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'ABSENSI HARI INI',
          style: TextStyle(
            color: Colors.grey[500],
            fontSize: 11,
            fontWeight: FontWeight.w500,
            letterSpacing: 0.5,
          ),
        ),
        const SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.start,
          children: [
            _buildAttendanceItem(
              icon: Icons.login_rounded,
              label: 'Masuk',
              value: checkIn,
              badge: checkInStatus,
              badgeColor: checkInStatus == 'Tepat Waktu'
                  ? const Color(0xFF3B6D11)
                  : const Color(0xFF854F0B),
              badgeBgColor: checkInStatus == 'Tepat Waktu'
                  ? const Color(0xFFEAF3DE)
                  : const Color(0xFFFAEEDA),
            ),
            _buildDivider(),
            _buildAttendanceItem(
              icon: Icons.logout_rounded,
              label: 'Pulang',
              value: checkOut,
              badge: checkOut == '—' ? 'Belum Absen' : '',
              badgeColor: const Color(0xFF5F5E5A),
              badgeBgColor: const Color(0xFFF1EFE8),
            ),
            _buildDivider(),
            _buildAttendanceItem(
              icon: Icons.access_time_rounded,
              label: 'Durasi',
              value: duration,
              badge: duration == '—' ? '' : 'berjalan',
              badgeColor: Colors.grey,
              badgeBgColor: Colors.transparent,
              isSmallValue: true,
            ),
          ],
        ),
      ],
    ),
  );
}

Widget _buildAttendanceItem({
  required IconData icon,
  required String label,
  required String value,
  required String badge,
  required Color badgeColor,
  required Color badgeBgColor,
  bool isSmallValue = false,
}) {
  return Expanded(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.start,
      children: [
        Icon(icon, color: AppColors.primaryPurple, size: 22),
        const SizedBox(height: 5),
        Text(
          label,
          style: TextStyle(
            fontSize: 10,
            color: Colors.grey[500],
          ),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: TextStyle(
            fontSize: isSmallValue ? 14 : 17,
            fontWeight: FontWeight.w600,
            color: value == '—' ? Colors.grey[400] : Colors.black87,
          ),
        ),
        const SizedBox(height: 5),

        // ✅ Beri tinggi fixed agar semua kolom sama tinggi
        SizedBox(
          height: 20,
          child: badge.isNotEmpty
              ? Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: badgeBgColor,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    badge,
                    style: TextStyle(
                      fontSize: 10,
                      fontWeight: FontWeight.w500,
                      color: badgeColor,
                    ),
                  ),
                )
              : const SizedBox.shrink(),
        ),
      ],
    ),
  );
}

Widget _buildDivider() {
  return Container(
    width: 0.5,
    height: 60,
    color: Colors.grey[200],
    margin: const EdgeInsets.symmetric(horizontal: 4),
  );
}