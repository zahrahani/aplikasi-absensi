import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/core/theme/app_font.dart';

void showAlert({
  required BuildContext context,
  required String message,
  required String alertStatus,
}) {
  Icon alertIcon = Icon(Icons.check_circle_outline, color: Colors.white, size: 20);
  Color alertColor = AppColors.primaryPurple;

  if (alertStatus == "success") {
    alertIcon = Icon(Icons.check_circle_outline, color: Colors.white, size: 20);
    alertColor = AppColors.primaryPurple;
  } else if (alertStatus == "danger") {
    alertIcon = Icon(Icons.error_outline, color: Colors.white, size: 20);
    alertColor = Color(0xFFE53E3E);
  }

  ScaffoldMessenger.of(context).showSnackBar(
    SnackBar(
      content: Row(
        crossAxisAlignment: CrossAxisAlignment.start, // icon rata atas jika teks banyak
        children: [
          alertIcon,
          const SizedBox(width: 8),
          Expanded( // teks mengikuti sisa lebar, tidak overflow
            child: Text(
              message,
              style: TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.w500,
                fontFamily: AppFont.fontDefault,
              ),
            ),
          ),
        ],
      ),
      backgroundColor: alertColor,
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      margin: const EdgeInsets.all(16),
      duration: const Duration(seconds: 3),
    ),
  );
}