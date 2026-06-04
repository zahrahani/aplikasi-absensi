
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildTabChip({
  required int index, 
  required String label, 
  required int selectedTab,
  required void Function(int) changeIndex
}) {
    final bool sel = selectedTab == index;
    return GestureDetector(
      onTap: () => changeIndex(index),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding:
            const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: sel ? AppColors.primaryPurple : Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
                color: Colors.black.withOpacity(0.06),
                blurRadius: 4,
                offset: const Offset(0, 1))
          ],
        ),
        child: Text(label,
            style: TextStyle(
                color: sel ? Colors.white : const Color(0xFF555555),
                fontSize: 12,
                fontWeight: FontWeight.w600)),
      ),
    );
  }