 // Widget: Satu sudut scanner (garis siku L)
  // isTop: true = atas, false = bawah
  // isLeft: true = kiri, false = kanan
import 'package:flutter/material.dart';

Widget buildCorner(
    double length,
    double thickness,
    double radius, {
    required bool isTop,
    required bool isLeft,
  }) {
    return SizedBox(
      width: length,
      height: length,
      child: Stack(
        children: [
          // Garis horizontal
          Positioned(
            top: isTop ? 0 : null,
            bottom: isTop ? null : 0,
            left: isLeft ? 0 : null,
            right: isLeft ? null : 0,
            child: Container(
              width: length,
              height: thickness,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(radius),
              ),
            ),
          ),
          // Garis vertikal
          Positioned(
            top: isTop ? 0 : null,
            bottom: isTop ? null : 0,
            left: isLeft ? 0 : null,
            right: isLeft ? null : 0,
            child: Container(
              width: thickness,
              height: length,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(radius),
              ),
            ),
          ),
        ],
      ),
    );
  }