// Widget: Tombol Batal
import 'package:flutter/material.dart';

Widget buildBatalButton(BuildContext context) {
    return SizedBox(
      width: 125,
      height: 35,
      child: ElevatedButton(
        onPressed: () {
          // Kembali ke halaman sebelumnya
          Navigator.pushReplacementNamed(
            context,
            '/main'
          );
        },
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.white,
          foregroundColor: const Color(0xFF1900A7),
          elevation: 0,
          shape: const StadiumBorder(), // Bentuk pill/rounded penuh
        ),
        child: const Text(
          'Batal',
          style: TextStyle(
            fontFamily: 'Roboto',
            fontSize: 18,
            fontWeight: FontWeight.w900,
            color: Color(0xFF1900A7),
          ),
        ),
      ),
    );
  }