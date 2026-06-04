import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildSummaryCard(
    String count, String label, bool isSelected, VoidCallback onTap) {
  return GestureDetector(
    onTap: onTap,
    child: AnimatedContainer(
      duration: const Duration(milliseconds: 200),
      padding: const EdgeInsets.symmetric(vertical: 14),
      decoration: BoxDecoration(
        color: isSelected ? const Color(0xFF4A3BCC) : Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            count,
            style: TextStyle(
              // Angka tetap oranye saat dipilih, ungu gelap saat tidak
              color: isSelected ? AppColors.warningColor : const Color(0xFF4A3BCC),
              fontSize: 22,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            style: TextStyle(
              // Label abu gelap agar terbaca di background putih
              color: isSelected
                  ? Colors.white.withOpacity(0.9)
                  : Colors.grey.shade600,
              fontSize: 12,
            ),
          ),
        ],
      ),
    ),
  );
}