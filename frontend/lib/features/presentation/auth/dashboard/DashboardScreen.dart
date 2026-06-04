import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/features/presentation/auth/dashboard/dashboardCard.dart';
import 'package:presensi/features/presentation/auth/dashboard/DashboardController.dart';
import 'package:presensi/features/widgets/header.dart';
// import 'package:presensi/features/widgets/showAlert.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  final int currentIndex;

  const DashboardScreen({super.key, required this.currentIndex});

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  Map userData       = {};
  Map? homeData;
  bool isLoading     = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    _initUser();
  }

  @override
  void didUpdateWidget(covariant DashboardScreen oldWidget) {
    super.didUpdateWidget(oldWidget);

    if (widget.currentIndex == 0 &&
        oldWidget.currentIndex != 0) {

      _initUser(); // reload shared preferences
      _loadHomeData(); // kalau perlu refresh data server
    }
  }

  // ── Ambil user dari shared preferences ───────────────────
  void _initUser() {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return;

    setState(() {
      userData = jsonDecode(userString);
    });

    _loadHomeData();
  }

  // ── Ambil data home dari server ───────────────────────────
  Future<void> _loadHomeData() async {
    setState(() {
      isLoading    = true;
      errorMessage = null;
    });

    final result = await DashboardController.getHomeData(ref);

    if (!mounted) return;

    setState(() {
      isLoading = false;
      if (result['success']) {
        homeData = result['data'];
      } else {
        errorMessage = result['messages'];
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF0F0F5),
      body: Column(
        children: [
          // ── Header ───────────────────────────────────────
          buildHeader(
            judulScreen: "Dashboard",
            nama       : userData['nama_lengkap'] ?? '-',
            jabatan    : userData['jabatan']       ?? '-',
            divisi     : userData['divisi']        ?? '-',
            fotoProfil: userData['foto_profil'] 
          ),

          // ── Body ─────────────────────────────────────────
          if (isLoading)
            const Expanded(
              child: Center(child: CircularProgressIndicator()),
            )
          else if (errorMessage != null)
            Expanded(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(errorMessage!),
                    const SizedBox(height: 12),
                    ElevatedButton(
                      onPressed: _loadHomeData,
                      child: const Text('Coba lagi'),
                    ),
                  ],
                ),
              ),
            )
          else
            // Kirim homeData ke DashboardCard─
            DashboardCard(
              homeData: homeData!,
              onRefresh: _loadHomeData  
            ),
        ],
      ),
    );
  }
}