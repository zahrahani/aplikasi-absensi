// Widget: Lingkaran blur/glow

import 'package:flutter/material.dart';

Widget buildBlurCircle(double size, Color color) {
  return Container(
    width: size,
    height: size,
    decoration: BoxDecoration(
      shape: BoxShape.circle,
      color: Colors.transparent,
      boxShadow: [
        BoxShadow(
          color: color.withOpacity(0.7),
          blurRadius: 180,
          spreadRadius: 80,
        ),
      ],
    ),
  );
}