import 'package:flutter/material.dart';

Widget buildSectionCard({
  required String        title,
  required List<Widget>  children,
}) {
  return Container(
    decoration: BoxDecoration(
      color       : Colors.white,
      borderRadius: BorderRadius.circular(16),
      boxShadow   : [
        BoxShadow(
          color     : Colors.black.withOpacity(0.04),
          blurRadius: 8,
          offset    : const Offset(0, 2),
        ),
      ],
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 14, 16, 8),
          child: Text(
            title,
            style: const TextStyle(
              fontSize    : 11,
              fontWeight  : FontWeight.bold,
              color       : Color(0xFF888888),
              letterSpacing: 0.5,
            ),
          ),
        ),
        const Divider(height: 1, color: Color(0xFFF0F0F0)),
        ...children,
      ],
    ),
  );
}