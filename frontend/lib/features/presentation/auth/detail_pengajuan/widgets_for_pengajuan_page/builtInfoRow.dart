import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget builtInfoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
      child: Row(children: [
        Icon(icon, size: 18, color: AppColors.primaryPurple.withOpacity(0.6)),
        const SizedBox(width: 12),
        Expanded(
            child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
          Text(label,
              style: const TextStyle(
                  fontSize: 11, color: Color(0xFF888888))),
          const SizedBox(height: 2),
          Text(value,
              style: const TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: Color(0xFF1A1A2E))),
        ])),
      ]),
    );
  }