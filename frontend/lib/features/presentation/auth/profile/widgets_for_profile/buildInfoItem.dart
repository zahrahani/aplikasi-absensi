import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildInfoItem({
  required IconData icon,
  required String   label,
  required String   value,
  bool isLast = false,
}) {
  return Column(
    children: [
      Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        child: Row(
          children: [
            Icon(icon, size: 18, color: AppColors.primaryPurple.withOpacity(0.7)),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    label,
                    style: const TextStyle(
                      fontSize: 11,
                      color   : Color(0xFF888888),
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    value,
                    style: const TextStyle(
                      fontSize  : 14,
                      fontWeight: FontWeight.w500,
                      color     : Color(0xFF1A1A2E),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      if (!isLast)
        const Divider(height: 1, indent: 46, color: Color(0xFFF0F0F0)),
    ],
  );
}