// Widget: untuk menampilkan shift kerja hari ini
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildShiftCard({
  required String namaShift,
  required String jamMasuk,
  required String jamPulang,
  required String batasTelat,
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
        // ── Label ─────────────────────────────────────────
        Text(
          'JADWAL SHIFT HARI INI',
          style: TextStyle(
            color: Colors.grey[500],
            fontSize: 11,
            fontWeight: FontWeight.w500,
            letterSpacing: 0.5,
          ),
        ),
        const SizedBox(height: 16),

        // ── Nama shift ────────────────────────────────────
        Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppColors.primaryPurple.withOpacity(0.08),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(
                Icons.work_outline_rounded,
                color: AppColors.primaryPurple,
                size: 18,
              ),
            ),
            const SizedBox(width: 10),
            Text(
              namaShift,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: Colors.black87,
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),

        // ── 3 item: masuk, pulang, batas telat ────────────
        Row(
          mainAxisAlignment: MainAxisAlignment.start,
          children: [
            _buildShiftItem(
              icon : Icons.login_rounded,
              label: 'Jam Masuk',
              value: jamMasuk,
            ),
            _buildShiftDivider(),
            _buildShiftItem(
              icon : Icons.logout_rounded,
              label: 'Jam Pulang',
              value: jamPulang,
            ),
            _buildShiftDivider(),
            _buildShiftItem(
              icon : Icons.warning_amber_rounded,
              label: 'Batas Telat',
              value: batasTelat,
              valueColor: const Color(0xFF854F0B),
            ),
          ],
        ),
      ],
    ),
  );
}

Widget _buildShiftItem({
  required IconData icon,
  required String label,
  required String value,
  Color? valueColor,
}) {
  return Expanded(
    child: Column(
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
            fontSize: 17,
            fontWeight: FontWeight.w600,
            color: valueColor ?? Colors.black87,
          ),
        ),
      ],
    ),
  );
}

Widget _buildShiftDivider() {
  return Container(
    width: 0.5,
    height: 60,
    color: Colors.grey[200],
    margin: const EdgeInsets.symmetric(horizontal: 4),
  );
}