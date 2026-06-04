// Widget: Welcome Text
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_font.dart';

Widget buildWelcomeText([String h1 = "Selamat Datang", String h2 = "Catat kehadiran hanya dengan ", String h2Bold = "sekali pakai."]) {
  return Column(
    mainAxisSize: MainAxisSize.min,
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      Text(
        h1,
        style: AppFont.h1TextStyle,
      ),
      SizedBox(height: 12),
      RichText(
        text: TextSpan(
          style: TextStyle(
            fontFamily: AppFont.fontDefault,
            color: Colors.white.withOpacity(0.72),
            fontSize: 15,
            height: 1.5,
          ),
          children: [
            TextSpan(text: h2),
            TextSpan(
              text: h2Bold,
              style: TextStyle(
                fontWeight: AppFont.h2Bold,
                color: Colors.white,
              ),
            ),
          ],
        ),
      ),
    ],
  );
}