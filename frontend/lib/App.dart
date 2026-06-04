import 'package:flutter/material.dart';
import 'package:presensi/core/middleware/AuthScreen.dart';
import 'package:presensi/core/middleware/GuestScreen.dart';
import 'package:presensi/features/presentation/auth/MainShell.dart';
import 'package:presensi/features/presentation/auth/scan_qr/ScanQRScreen.dart';
import 'package:presensi/features/presentation/guest/login/LoginScreen.dart';


class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      // Menghilangkan banner debug
      debugShowCheckedModeBanner: false,

      // Nama aplikasi
      title: 'Aplikasi Absensi',

      // Theme global aplikasi
      theme: ThemeData(
        useMaterial3: true,
      ),

      // Route pertama saat aplikasi dibuka
      initialRoute: '/login',

      // Routing aplikasi
      routes: {
        '/login': (context) => const GuestScreen(child: LoginScreen()),
        '/main': (context) => const AuthScreen(child: MainShell()),
        // '/login': (context) => LoginScreen(),
        // '/main': (context) => const MainShell(),
        '/scan-qr': (context) => const ScanQRScreen()
      },

    );
  }
}
