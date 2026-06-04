import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/statusPengajuanController.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildPengajuanCard.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildShowBuatPengajuanSheet.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildTabChip.dart';
import 'package:presensi/features/widgets/header.dart';
import 'package:presensi/features/widgets/showAlert.dart';

class StatusPengajuanScreen extends ConsumerStatefulWidget {
  final int currentIndex;
  const StatusPengajuanScreen({super.key, required this.currentIndex});

  @override
  ConsumerState<StatusPengajuanScreen> createState() =>
      _StatusPengajuanScreenState();
}

class _StatusPengajuanScreenState
    extends ConsumerState<StatusPengajuanScreen> {
  int    _selectedTab  = 0;
  String _searchQuery  = '';
  Map    userData      = {};
  bool   isLoading     = true;
  String? errorMessage;

  final TextEditingController _searchCtrl = TextEditingController();

  // ── Data dari server ───────────────────────────────────────
  List<PengajuanItem> _allData = [];

  // ── Filter berdasarkan tab & search ───────────────────────
  List<PengajuanItem> get _filtered {
    final statusMap = [
      PengajuanStatus.menunggu,
      PengajuanStatus.disetujui,
      PengajuanStatus.ditolak,
    ];
    return _allData.where((e) {
      final matchTab    = e.status == statusMap[_selectedTab];
      final matchSearch = _searchQuery.isEmpty ||
          e.namaJenis.toLowerCase().contains(_searchQuery.toLowerCase()) ||
          e.alasan.toLowerCase().contains(_searchQuery.toLowerCase());
      return matchTab && matchSearch;
    }).toList();
  }

  int _count(PengajuanStatus s) =>
      _allData.where((e) => e.status == s).length;

  @override
  void initState() {
    super.initState();
    _initUser();
    _loadPengajuan();
  }

  @override
  void didUpdateWidget(covariant StatusPengajuanScreen oldWidget) {
    super.didUpdateWidget(oldWidget);

    if (widget.currentIndex == 2 &&
        oldWidget.currentIndex != 2) {

      _loadPengajuan();
      _initUser();
      
    }
  }


  void _initUser() {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return;
    setState(() => userData = jsonDecode(userString));
    _loadPengajuan();
  }

  // ── Fetch dari server ──────────────────────────────────────
  Future<void> _loadPengajuan() async {
    setState(() {
      isLoading    = true;
      errorMessage = null;
    });

    final result = await StatusPengajuanController.getPengajuan(ref);

    if (!mounted) return;

    setState(() {
      isLoading = false;
      if (result['success']) {
        _allData = List<PengajuanItem>.from(result['data']);
      } else {
        errorMessage = result['messages'];
      }
    });

  }

  // ── Batalkan pengajuan ─────────────────────────────────────
  Future<void> _batalPengajuan(PengajuanItem item) async {
    final result = await StatusPengajuanController.batalPengajuan(
      ref         : ref,
      pengajuanId : item.pengajuanId,
    );

    if (!mounted) return;

    showAlert(
      context    : context,
      message    : result['messages'],
      alertStatus: result['success'] ? 'success' : 'danger',
    );

    if (result['success']) {
      _loadPengajuan();
    }
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
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
          Expanded(
            child: Container(
              color: AppColors.whiteBackground,
              child: Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 20, 16, 0),
                    child: Column(
                      children: [
                        // ── Search ──────────────────────────
                        Container(
                          height: 46,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(30),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.05),
                                blurRadius: 6,
                                offset: const Offset(0, 2),
                              ),
                            ],
                          ),
                          child: TextField(
                            controller: _searchCtrl,
                            onChanged: (v) =>
                                setState(() => _searchQuery = v),
                            decoration: const InputDecoration(
                              hintText: 'Cari pengajuan...',
                              hintStyle: TextStyle(
                                  color: Color(0xFFAAAAAA), fontSize: 14),
                              prefixIcon: Icon(Icons.search,
                                  color: Color(0xFFAAAAAA), size: 20),
                              border: InputBorder.none,
                              contentPadding:
                                  EdgeInsets.symmetric(vertical: 13),
                            ),
                          ),
                        ),
                        const SizedBox(height: 14),

                        // ── Tabs ─────────────────────────────
                        Row(
                          children: [
                            buildTabChip(
                              index     : 0,
                              label     : 'Menunggu ${_count(PengajuanStatus.menunggu)}',
                              selectedTab: _selectedTab,
                              changeIndex: (i) =>
                                  setState(() => _selectedTab = i),
                            ),
                            const SizedBox(width: 8),
                            buildTabChip(
                              index     : 1,
                              label     : 'Disetujui ${_count(PengajuanStatus.disetujui)}',
                              selectedTab: _selectedTab,
                              changeIndex: (i) =>
                                  setState(() => _selectedTab = i),
                            ),
                            const SizedBox(width: 8),
                            buildTabChip(
                              index     : 2,
                              label     : 'Ditolak ${_count(PengajuanStatus.ditolak)}',
                              selectedTab: _selectedTab,
                              changeIndex: (i) =>
                                  setState(() => _selectedTab = i),
                            ),
                          ],
                        ),
                        const SizedBox(height: 14),

                        // ── Tombol buat pengajuan ─────────────
                        SizedBox(
                          width : double.infinity,
                          height: 46,
                          child: ElevatedButton.icon(
                            onPressed: () => buildShowBuatPengajuanSheet(
                              context      : context,
                              ref          : ref,
                              onSubmitted  : () {
                                _loadPengajuan();
                              },
                            ),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppColors.primaryPurple,
                              foregroundColor: Colors.white,
                              shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12)),
                              elevation: 0,
                            ),
                            icon : const Icon(Icons.add, size: 20),
                            label: const Text('Buat Pengajuan Baru',
                                style: TextStyle(
                                    fontWeight: FontWeight.w600,
                                    fontSize: 15)),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 14),

                  // ── List ──────────────────────────────────
                  Expanded(
                    child: isLoading
                        ? const Center(child: CircularProgressIndicator())
                        : errorMessage != null
                            ? Center(
                                child: Column(
                                  mainAxisAlignment:
                                      MainAxisAlignment.center,
                                  children: [
                                    Text(errorMessage!),
                                    const SizedBox(height: 12),
                                    ElevatedButton(
                                      onPressed: _loadPengajuan,
                                      child: const Text('Coba lagi'),
                                    ),
                                  ],
                                ),
                              )
                            : RefreshIndicator(
                                onRefresh: _loadPengajuan,
                                child: _filtered.isEmpty
                                    ? ListView(
                                        children: [
                                          SizedBox(
                                            height: 200,
                                            child: Center(
                                              child: Column(
                                                mainAxisSize:
                                                    MainAxisSize.min,
                                                children: [
                                                  Icon(
                                                    Icons.inbox_outlined,
                                                    size: 52,
                                                    color: Colors.grey[400],
                                                  ),
                                                  const SizedBox(height: 8),
                                                  Text(
                                                    'Tidak ada pengajuan',
                                                    style: TextStyle(
                                                      color: Colors.grey[400],
                                                      fontSize: 14,
                                                    ),
                                                  ),
                                                ],
                                              ),
                                            ),
                                          ),
                                        ],
                                      )
                                    : ListView.separated(
                                        padding: const EdgeInsets.fromLTRB(
                                            16, 0, 16, 16),
                                        itemCount: _filtered.length,
                                        separatorBuilder: (_, __) =>
                                            const SizedBox(height: 14),
                                        itemBuilder: (_, i) =>
                                            buildPengajuanCard(
                                          item       : _filtered[i],
                                          onCancelled: () =>
                                              _batalPengajuan(_filtered[i]),
                                        ),
                                      ),
                              ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}