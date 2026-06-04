import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_size.dart';

class AppFont {

  // Jenis Font Default
  static String fontDefault = 'Roboto';

  // Ukuran bold h2 atau sub judul
  static FontWeight h2Bold = FontWeight.w700;


  // H1 TextSytle
  static TextStyle h1TextStyle = TextStyle(
    fontFamily: AppFont.fontDefault,
    color: Colors.white,
    fontSize: AppSize.h1Size,
    fontWeight: FontWeight.w900,
    height: 1.1,
  );
}