import 'package:flutter/material.dart';

class buildSmallButton extends StatelessWidget {
  final String label;
  final Color textColor, borderColor, bgColor;
  final VoidCallback onPressed;
  const buildSmallButton(
      {super.key, required this.label,
      required this.textColor,
      required this.borderColor,
      required this.bgColor,
      required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return OutlinedButton(
      onPressed: onPressed,
      style: OutlinedButton.styleFrom(
        foregroundColor: textColor,
        side: BorderSide(color: borderColor),
        backgroundColor: bgColor,
        padding:
            const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
        minimumSize: Size.zero,
        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8)),
      ),
      child: Text(label,
          style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: textColor)),
    );
  }
}