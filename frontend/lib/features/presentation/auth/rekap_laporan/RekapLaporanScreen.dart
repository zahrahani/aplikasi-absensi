import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/rekapLaporanCard.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/rekapLaporanController.dart';
import 'package:presensi/features/widgets/header.dart';

class RekapLaporanScreen extends ConsumerStatefulWidget {
  final int currentIndex;
  const RekapLaporanScreen({super.key, required this.currentIndex});

  @override
  ConsumerState<RekapLaporanScreen> createState() => _RekapLaporanScreenState();
}

class _RekapLaporanScreenState extends ConsumerState<RekapLaporanScreen> {
  Map     userData     = {};
  Map?    rekapData;
  bool    isLoading    = true;
  String? errorMessage;

  // ← bulan & tahun aktif, default bulan ini
  int _selectedMonth = DateTime.now().month;
  int _selectedYear  = DateTime.now().year;

  @override
  void initState() {
    super.initState();
    _initUser();
    _loadRekap();
  }

  @override
  void didUpdateWidget(covariant RekapLaporanScreen oldWidget) {
    super.didUpdateWidget(oldWidget);

    if (widget.currentIndex == 1 &&
        oldWidget.currentIndex != 1) {

      _loadRekap();
      _initUser();
      
    }
  }
  

  void _initUser() {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return;

    setState(() {
      userData = jsonDecode(userString);
    });

    _loadRekap();
  }

  // ── Fetch rekap berdasarkan bulan & tahun aktif ───────────
  Future<void> _loadRekap() async {
    setState(() {
      isLoading    = true;
      errorMessage = null;
    });

    final result = await RekapLaporanController.getRekap(
      ref  : ref,
      month: _selectedMonth,
      year : _selectedYear,
    );

    if (!mounted) return;

    setState(() {
      isLoading = false;
      if (result['success']) {
        rekapData = result['data'];
      } else {
        errorMessage = result['messages'];
      }
    });
  }

  // ── Dipanggil saat user pilih bulan baru ──────────────────
  void _onMonthChanged(int month, int year) {
    setState(() {
      _selectedMonth = month;
      _selectedYear  = year;
    });
    _loadRekap(); // ← fetch ulang dengan bulan baru
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF0F0F5),
      body: Column(
        children: [
          buildHeader(
            judulScreen: "Dashboard",
            nama       : userData['nama_lengkap'] ?? '-',
            jabatan    : userData['jabatan']       ?? '-',
            divisi     : userData['divisi']        ?? '-',
            fotoProfil: userData['foto_profil'] 
          ),

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
                      onPressed: _loadRekap,
                      child: const Text('Coba lagi'),
                    ),
                  ],
                ),
              ),
            )
          else
            RekapLaporanCard(
            rekapData: rekapData!,
            onRefresh: _loadRekap,
            onMonthChanged: _onMonthChanged,
            selectedMonth: _selectedMonth,
            selectedYear: _selectedYear,
          )
        ],
      ),
    );
  }
}