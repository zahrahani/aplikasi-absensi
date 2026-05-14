import 'package:flutter/material.dart';
import 'scan_qr_page.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'QR Scanner',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        fontFamily: 'Roboto',
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF1900A7),
        ),
        useMaterial3: true,
      ),
      // Langsung masuk ke halaman QR Scanner
      home: const QrScannerPage(),
    );
  }
}