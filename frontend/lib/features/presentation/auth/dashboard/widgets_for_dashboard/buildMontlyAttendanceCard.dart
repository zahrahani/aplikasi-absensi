// Widget: untuk menampilkan kehadiran bulan ini
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildMonthlyAttendanceCard({
  required int hadir,
  required int telat,
  required int izin,
  required int sakit,
  required int alpha,
  required int totalHariKerja,
}) {
  final double percentage = totalHariKerja == 0
      ? 0
      : (hadir / totalHariKerja).clamp(0.0, 1.0);

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
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              'KEHADIRAN BULAN INI',
              style: TextStyle(
                color: Colors.grey[500],
                fontSize: 11,
                fontWeight: FontWeight.w500,
                letterSpacing: 0.5,
              ),
            ),
            Text(
              '${(percentage * 100).toStringAsFixed(0)}%',
              style: TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.primaryPurple,
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        ClipRRect(
          borderRadius: BorderRadius.circular(99),
          child: LinearProgressIndicator(
            value: percentage,
            minHeight: 6,
            backgroundColor: const Color(0xFFEEEDFE),
            valueColor: AlwaysStoppedAnimation<Color>(AppColors.primaryPurple),
          ),
        ),
        const SizedBox(height: 14),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            _buildStatItem(label: 'Hadir', value: hadir, color: AppColors.successColor),
            _buildStatItem(label: 'Telat', value: telat, color: AppColors.purpleColor),
            _buildStatItem(label: 'Izin', value: izin, color: AppColors.primaryPurple),
            _buildStatItem(label: 'Sakit', value: sakit, color: AppColors.secondaryColor),
            _buildStatItem(label: 'Alpha', value: alpha, color: AppColors.dangerColor),
          ],
        ),
      ],
    ),
  );
}

Widget _buildStatItem({
  required String label,
  required int value,
  required Color color,
}) {
  return Column(
    children: [
      Text(
        '$value',
        style: TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.w600,
          color: color,
        ),
      ),
      const SizedBox(height: 2),
      Text(
        label,
        style: TextStyle(
          fontSize: 10,
          color: Colors.grey[500],
        ),
      ),
    ],
  );
}