// Ini merupakan kumpulan widget untuk mempercantik background atau tampilan
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:presensi/core/theme/app_font.dart';

// Widget: Badge perusahaan
Widget buildCompanyBadge() {
  return Container(
    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 2.5),
    decoration: BoxDecoration(
      color: Colors.white.withOpacity(0.15),
      borderRadius: BorderRadius.circular(50),
      border: Border.all(
        color: Colors.white.withOpacity(0.25),
        width: 1,
      ),
    ),
    child: Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(
          Icons.business_rounded,
          color: Colors.white.withOpacity(0.9),
          size: 15,
        ),
        const SizedBox(width: 7),
        Text(
           dotenv.env['APP_NAME'] ?? 'Presensi App',
          style: TextStyle(
            fontFamily: AppFont.fontDefault,
            color: Colors.white.withOpacity(0.95),
            fontSize: 12,
            fontWeight: FontWeight.w500,
            letterSpacing: 0.3,
          ),
        ),
      ],
    ),
  );
}