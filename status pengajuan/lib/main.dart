import 'package:flutter/material.dart';

// ─────────────────────────────────────────────
//  ENTRY POINT
// ─────────────────────────────────────────────
void main() => runApp(const MyApp());

class MyApp extends StatelessWidget {
  const MyApp({super.key});
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Status Pengajuan',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(fontFamily: 'Roboto', useMaterial3: true),
      home: const MainShell(),
    );
  }
}

// ─────────────────────────────────────────────
//  CONSTANTS
// ─────────────────────────────────────────────
const kPrimary = Color(0xFF2D1DB5);
const kBg = Color(0xFFF0F0F8);
const kOrange = Color(0xFFF5A623);
const kGreen = Color(0xFF4CAF50);
const kRed = Color(0xFFE74C3C);

// ─────────────────────────────────────────────
//  DATA MODEL
// ─────────────────────────────────────────────
enum PengajuanStatus { menunggu, disetujui, ditolak }

enum StepState { done, waiting, pending }

class ApprovalStep {
  final String label;
  final String time;
  final StepState state;
  const ApprovalStep(
      {required this.label, required this.time, required this.state});
}

class PengajuanItem {
  final String id;
  final String emoji;
  final Color emojiBg;
  final String title;
  final String dateRange;
  final String submittedDate;
  PengajuanStatus status;
  final List<ApprovalStep> steps;
  final String catatan;

  PengajuanItem({
    required this.id,
    required this.emoji,
    required this.emojiBg,
    required this.title,
    required this.dateRange,
    required this.submittedDate,
    required this.status,
    required this.steps,
    this.catatan = '-',
  });
}

// ─────────────────────────────────────────────
//  GLOBAL DATA STORE
// ─────────────────────────────────────────────
final List<PengajuanItem> globalData = [
  // ── MENUNGGU ──
  PengajuanItem(
    id: 'P001',
    emoji: '🏝️',
    emojiBg: Color(0xFFFFF3E0),
    title: 'Pengajuan Cuti Tahunan',
    dateRange: '2 Mar – 4 Mar 2026 · 3 hari kerja',
    submittedDate: '24 Feb 2026',
    status: PengajuanStatus.menunggu,
    catatan: 'Liburan keluarga ke Bali',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '24 Feb, 09:10',
          state: StepState.done),
      ApprovalStep(
          label: 'Menunggu persetujuan Admin',
          time: '–',
          state: StepState.waiting),
      ApprovalStep(
          label: 'Keputusan final', time: '–', state: StepState.pending),
    ],
  ),
  PengajuanItem(
    id: 'P002',
    emoji: '💻',
    emojiBg: Color(0xFFEEEEEE),
    title: 'Pengajuan WFH',
    dateRange: 'Senin, 3 Mar 2026 · 1 hari',
    submittedDate: '25 Feb 2026',
    status: PengajuanStatus.menunggu,
    catatan: 'Menunggu teknisi perbaikan AC kantor',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '25 Feb, 08:30',
          state: StepState.done),
      ApprovalStep(
          label: 'Menunggu persetujuan Admin',
          time: '–',
          state: StepState.waiting),
      ApprovalStep(
          label: 'Keputusan final', time: '–', state: StepState.pending),
    ],
  ),
  // ── DISETUJUI ──
  PengajuanItem(
    id: 'P003',
    emoji: '🏠',
    emojiBg: Color(0xFFE8F5E9),
    title: 'Pengajuan WFH',
    dateRange: 'Jumat, 14 Feb 2026 · 1 hari',
    submittedDate: '12 Feb 2026',
    status: PengajuanStatus.disetujui,
    catatan: 'Keperluan keluarga mendadak',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '12 Feb, 10:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Disetujui oleh Admin',
          time: '13 Feb, 09:15',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Disetujui',
          time: '13 Feb, 09:15',
          state: StepState.done),
    ],
  ),
  PengajuanItem(
    id: 'P004',
    emoji: '🏝️',
    emojiBg: Color(0xFFFFF3E0),
    title: 'Pengajuan Cuti Tahunan',
    dateRange: '20 Jan – 22 Jan 2026 · 3 hari kerja',
    submittedDate: '15 Jan 2026',
    status: PengajuanStatus.disetujui,
    catatan: 'Acara pernikahan saudara di Yogyakarta',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '15 Jan, 08:45',
          state: StepState.done),
      ApprovalStep(
          label: 'Disetujui oleh Admin',
          time: '16 Jan, 11:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Disetujui',
          time: '16 Jan, 11:00',
          state: StepState.done),
    ],
  ),
  PengajuanItem(
    id: 'P005',
    emoji: '🏥',
    emojiBg: Color(0xFFE3F2FD),
    title: 'Pengajuan Cuti Sakit',
    dateRange: '5 Jan 2026 · 1 hari',
    submittedDate: '5 Jan 2026',
    status: PengajuanStatus.disetujui,
    catatan: 'Demam dan flu, terlampir surat dokter',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '5 Jan, 07:30',
          state: StepState.done),
      ApprovalStep(
          label: 'Disetujui oleh Admin',
          time: '5 Jan, 08:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Disetujui',
          time: '5 Jan, 08:00',
          state: StepState.done),
    ],
  ),
  PengajuanItem(
    id: 'P006',
    emoji: '💻',
    emojiBg: Color(0xFFEEEEEE),
    title: 'Pengajuan WFH',
    dateRange: 'Rabu, 17 Des 2025 · 1 hari',
    submittedDate: '16 Des 2025',
    status: PengajuanStatus.disetujui,
    catatan: 'Mengantar anak ke rumah sakit',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '16 Des, 16:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Disetujui oleh Admin',
          time: '16 Des, 17:30',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Disetujui',
          time: '16 Des, 17:30',
          state: StepState.done),
    ],
  ),
  PengajuanItem(
    id: 'P007',
    emoji: '📋',
    emojiBg: Color(0xFFF3E5F5),
    title: 'Pengajuan Lembur',
    dateRange: 'Sabtu, 6 Des 2025 · 4 jam',
    submittedDate: '5 Des 2025',
    status: PengajuanStatus.disetujui,
    catatan: 'Penyelesaian laporan akhir tahun',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '5 Des, 13:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Disetujui oleh Admin',
          time: '5 Des, 14:45',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Disetujui',
          time: '5 Des, 14:45',
          state: StepState.done),
    ],
  ),
  // ── DITOLAK ──
  PengajuanItem(
    id: 'P008',
    emoji: '🏝️',
    emojiBg: Color(0xFFFFF3E0),
    title: 'Pengajuan Cuti Tahunan',
    dateRange: '1 Mar – 5 Mar 2026 · 5 hari kerja',
    submittedDate: '20 Feb 2026',
    status: PengajuanStatus.ditolak,
    catatan: 'Ditolak karena bentrok dengan deadline proyek Q1',
    steps: [
      ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time: '20 Feb, 11:00',
          state: StepState.done),
      ApprovalStep(
          label: 'Ditolak oleh Admin',
          time: '21 Feb, 09:30',
          state: StepState.done),
      ApprovalStep(
          label: 'Keputusan final: Ditolak',
          time: '21 Feb, 09:30',
          state: StepState.done),
    ],
  ),
];

// ─────────────────────────────────────────────
//  MAIN SHELL  (bottom nav wrapper)
// ─────────────────────────────────────────────
class MainShell extends StatefulWidget {
  const MainShell({super.key});
  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _navIndex = 2;
  void _refresh() => setState(() {});

  @override
  Widget build(BuildContext context) {
    final pages = [
      _PlaceholderPage(icon: Icons.grid_view_rounded, label: 'Beranda'),
      _PlaceholderPage(icon: Icons.bar_chart_rounded, label: 'Rekap'),
      StatusPengajuanPage(onDataChanged: _refresh),
      _PlaceholderPage(icon: Icons.person_outline_rounded, label: 'Profil'),
    ];
    return Scaffold(
      body: IndexedStack(index: _navIndex, children: pages),
      bottomNavigationBar: _BottomNav(
        selectedIndex: _navIndex,
        onTap: (i) => setState(() => _navIndex = i),
      ),
    );
  }
}

// ─────────────────────────────────────────────
//  BOTTOM NAV
// ─────────────────────────────────────────────
class _BottomNav extends StatelessWidget {
  final int selectedIndex;
  final ValueChanged<int> onTap;
  const _BottomNav({required this.selectedIndex, required this.onTap});

  @override
  Widget build(BuildContext context) {
    final items = [
      (Icons.grid_view_rounded, 'Beranda'),
      (Icons.bar_chart_rounded, 'Rekap'),
      (Icons.assignment_outlined, 'Pengajuan'),
      (Icons.person_outline_rounded, 'Profil'),
    ];
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
              color: Color(0x14000000),
              blurRadius: 10,
              offset: Offset(0, -2))
        ],
      ),
      child: SafeArea(
        child: SizedBox(
          height: 62,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: List.generate(items.length, (i) {
              final active = i == selectedIndex;
              return GestureDetector(
                onTap: () => onTap(i),
                behavior: HitTestBehavior.opaque,
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(items[i].$1,
                        color: active ? kPrimary : const Color(0xFFAAAAAA),
                        size: 24),
                    const SizedBox(height: 3),
                    Text(items[i].$2,
                        style: TextStyle(
                          fontSize: 11,
                          color: active ? kPrimary : const Color(0xFFAAAAAA),
                          fontWeight: active
                              ? FontWeight.w600
                              : FontWeight.normal,
                        )),
                  ],
                ),
              );
            }),
          ),
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────
//  PLACEHOLDER PAGES
// ─────────────────────────────────────────────
class _PlaceholderPage extends StatelessWidget {
  final IconData icon;
  final String label;
  const _PlaceholderPage({required this.icon, required this.label});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kBg,
      body: Center(
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Icon(icon, size: 64, color: kPrimary.withOpacity(0.3)),
          const SizedBox(height: 12),
          Text('Halaman $label',
              style:
                  TextStyle(fontSize: 18, color: kPrimary.withOpacity(0.5))),
        ]),
      ),
    );
  }
}

// ─────────────────────────────────────────────
//  STATUS PENGAJUAN PAGE
// ─────────────────────────────────────────────
class StatusPengajuanPage extends StatefulWidget {
  final VoidCallback? onDataChanged;
  const StatusPengajuanPage({super.key, this.onDataChanged});
  @override
  State<StatusPengajuanPage> createState() => _StatusPengajuanPageState();
}

class _StatusPengajuanPageState extends State<StatusPengajuanPage> {
  int _selectedTab = 0;
  final TextEditingController _searchCtrl = TextEditingController();
  String _searchQuery = '';

  List<PengajuanItem> get _filtered {
    final statusMap = [
      PengajuanStatus.menunggu,
      PengajuanStatus.disetujui,
      PengajuanStatus.ditolak
    ];
    return globalData
        .where((e) =>
            e.status == statusMap[_selectedTab] &&
            (_searchQuery.isEmpty ||
                e.title
                    .toLowerCase()
                    .contains(_searchQuery.toLowerCase())))
        .toList();
  }

  int _count(PengajuanStatus s) =>
      globalData.where((e) => e.status == s).length;

  void _refresh() => setState(() {});

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kPrimary,
      body: SafeArea(
        child: Column(children: [
          // ── Header ──
          Padding(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
            child: Column(children: [
              const Text('Status Pengajuan',
                  style: TextStyle(
                      color: Colors.white,
                      fontSize: 20,
                      fontWeight: FontWeight.bold)),
              const SizedBox(height: 18),
              Row(children: [
                _summaryCard(
                    '${_count(PengajuanStatus.menunggu)}',
                    'Menunggu',
                    _selectedTab == 0,
                    () => setState(() => _selectedTab = 0)),
                const SizedBox(width: 10),
                _summaryCard(
                    '${_count(PengajuanStatus.disetujui)}',
                    'Disetujui',
                    _selectedTab == 1,
                    () => setState(() => _selectedTab = 1)),
                const SizedBox(width: 10),
                _summaryCard(
                    '${_count(PengajuanStatus.ditolak)}',
                    'Ditolak',
                    _selectedTab == 2,
                    () => setState(() => _selectedTab = 2)),
              ]),
            ]),
          ),
          const SizedBox(height: 16),
          // ── White Sheet ──
          Expanded(
            child: Container(
              decoration: const BoxDecoration(
                color: kBg,
                borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(28),
                    topRight: Radius.circular(28)),
              ),
              child: Column(children: [
                Padding(
                  padding: const EdgeInsets.fromLTRB(16, 20, 16, 0),
                  child: Column(children: [
                    // Search
                    Container(
                      height: 46,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(30),
                        boxShadow: [
                          BoxShadow(
                              color: Colors.black.withOpacity(0.05),
                              blurRadius: 6,
                              offset: const Offset(0, 2))
                        ],
                      ),
                      child: TextField(
                        controller: _searchCtrl,
                        onChanged: (v) => setState(() => _searchQuery = v),
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
                    // Tabs
                    Row(children: [
                      _tabChip(0,
                          '⏳ Menunggu ${_count(PengajuanStatus.menunggu)}'),
                      const SizedBox(width: 8),
                      _tabChip(1,
                          '✅ Disetujui ${_count(PengajuanStatus.disetujui)}'),
                      const SizedBox(width: 8),
                      _tabChip(2,
                          '✕ Ditolak ${_count(PengajuanStatus.ditolak)}'),
                    ]),
                    const SizedBox(height: 14),
                    // New Button
                    SizedBox(
                      width: double.infinity,
                      height: 46,
                      child: ElevatedButton.icon(
                        onPressed: () =>
                            _showBuatPengajuanSheet(context),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: kPrimary,
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12)),
                          elevation: 0,
                        ),
                        icon: const Icon(Icons.add, size: 20),
                        label: const Text('Buat Pengajuan Baru',
                            style: TextStyle(
                                fontWeight: FontWeight.w600,
                                fontSize: 15)),
                      ),
                    ),
                  ]),
                ),
                const SizedBox(height: 14),
                // List
                Expanded(
                  child: _filtered.isEmpty
                      ? Center(
                          child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                            Icon(Icons.inbox_outlined,
                                size: 52, color: Colors.grey[400]),
                            const SizedBox(height: 8),
                            Text('Tidak ada pengajuan',
                                style: TextStyle(
                                    color: Colors.grey[400],
                                    fontSize: 14)),
                          ]),
                        )
                      : ListView.separated(
                          padding:
                              const EdgeInsets.fromLTRB(16, 0, 16, 16),
                          itemCount: _filtered.length,
                          separatorBuilder: (_, __) =>
                              const SizedBox(height: 14),
                          itemBuilder: (_, i) => _PengajuanCard(
                            item: _filtered[i],
                            onCancelled: () {
                              _refresh();
                              widget.onDataChanged?.call();
                            },
                          ),
                        ),
                ),
              ]),
            ),
          ),
        ]),
      ),
    );
  }

  Widget _summaryCard(
      String count, String label, bool isSelected, VoidCallback onTap) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: isSelected
                ? const Color(0xFF4A3BCC)
                : Colors.white.withOpacity(0.15),
            borderRadius: BorderRadius.circular(14),
            border: isSelected
                ? Border.all(color: Colors.white.withOpacity(0.3))
                : null,
          ),
          child: Column(children: [
            Text(count,
                style: TextStyle(
                    color: isSelected ? kOrange : Colors.white,
                    fontSize: 22,
                    fontWeight: FontWeight.bold)),
            const SizedBox(height: 2),
            Text(label,
                style: TextStyle(
                    color: Colors.white.withOpacity(0.85), fontSize: 12)),
          ]),
        ),
      ),
    );
  }

  Widget _tabChip(int index, String label) {
    final bool sel = _selectedTab == index;
    return GestureDetector(
      onTap: () => setState(() => _selectedTab = index),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding:
            const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: sel ? kPrimary : Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
                color: Colors.black.withOpacity(0.06),
                blurRadius: 4,
                offset: const Offset(0, 1))
          ],
        ),
        child: Text(label,
            style: TextStyle(
                color: sel ? Colors.white : const Color(0xFF555555),
                fontSize: 12,
                fontWeight: FontWeight.w600)),
      ),
    );
  }

  void _showBuatPengajuanSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => BuatPengajuanSheet(
        onSubmitted: (item) {
          setState(() => globalData.add(item));
          widget.onDataChanged?.call();
        },
      ),
    );
  }
}

// ─────────────────────────────────────────────
//  PENGAJUAN CARD
// ─────────────────────────────────────────────
class _PengajuanCard extends StatelessWidget {
  final PengajuanItem item;
  final VoidCallback onCancelled;
  const _PengajuanCard({required this.item, required this.onCancelled});

  Color get _statusColor {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return kOrange;
      case PengajuanStatus.disetujui:
        return kGreen;
      case PengajuanStatus.ditolak:
        return kRed;
    }
  }

  Color get _statusBg {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return const Color(0xFFFFF3E0);
      case PengajuanStatus.disetujui:
        return const Color(0xFFE8F5E9);
      case PengajuanStatus.ditolak:
        return const Color(0xFFFFEBEE);
    }
  }

  IconData get _statusIcon {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return Icons.access_time;
      case PengajuanStatus.disetujui:
        return Icons.check_circle_outline;
      case PengajuanStatus.ditolak:
        return Icons.cancel_outlined;
    }
  }

  String get _statusLabel {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return 'Menunggu';
      case PengajuanStatus.disetujui:
        return 'Disetujui';
      case PengajuanStatus.ditolak:
        return 'Ditolak';
    }
  }

  @override
  Widget build(BuildContext context) {
    final bool canCancel = item.status == PengajuanStatus.menunggu;
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE8E8E8)),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 8,
              offset: const Offset(0, 2))
        ],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        // Header
        Padding(
          padding: const EdgeInsets.fromLTRB(14, 14, 14, 10),
          child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 44,
                  height: 44,
                  decoration: BoxDecoration(
                      color: item.emojiBg,
                      borderRadius: BorderRadius.circular(10)),
                  child: Center(
                      child: Text(item.emoji,
                          style: const TextStyle(fontSize: 22))),
                ),
                const SizedBox(width: 12),
                Expanded(
                    child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                      Text(item.title,
                          style: const TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.bold,
                              color: Color(0xFF1A1A2E))),
                      const SizedBox(height: 3),
                      Text(item.dateRange,
                          style: const TextStyle(
                              fontSize: 12, color: Color(0xFF888888))),
                    ])),
                Container(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                      color: _statusBg,
                      borderRadius: BorderRadius.circular(8)),
                  child: Row(mainAxisSize: MainAxisSize.min, children: [
                    Icon(_statusIcon, size: 12, color: _statusColor),
                    const SizedBox(width: 4),
                    Text(_statusLabel,
                        style: TextStyle(
                            color: _statusColor,
                            fontSize: 11,
                            fontWeight: FontWeight.w600)),
                  ]),
                ),
              ]),
        ),
        // Progress
        _ProgressSection(steps: item.steps, status: item.status),
        // Footer
        Padding(
          padding: const EdgeInsets.fromLTRB(14, 10, 14, 14),
          child: Row(children: [
            const Icon(Icons.calendar_today_outlined,
                size: 13, color: Color(0xFF888888)),
            const SizedBox(width: 5),
            Text('Diajukan ${item.submittedDate}',
                style: const TextStyle(
                    fontSize: 12, color: Color(0xFF888888))),
            const Spacer(),
            if (canCancel) ...[
              _SmallButton(
                label: 'Batalkan',
                textColor: kRed,
                borderColor: const Color(0xFFFFCDD2),
                bgColor: const Color(0xFFFFF5F5),
                onPressed: () => _confirmCancel(context),
              ),
              const SizedBox(width: 8),
            ],
            _SmallButton(
              label: 'Detail →',
              textColor: kPrimary,
              borderColor: const Color(0xFFBBBBDD),
              bgColor: Colors.white,
              onPressed: () => _openDetail(context),
            ),
          ]),
        ),
      ]),
    );
  }

  void _confirmCancel(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        shape:
            RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Batalkan Pengajuan',
            style:
                TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
        content:
            Text('Apakah Anda yakin ingin membatalkan "${item.title}"?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Tidak',
                style: TextStyle(color: Color(0xFF888888))),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
                backgroundColor: kRed,
                foregroundColor: Colors.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8))),
            onPressed: () {
              globalData.remove(item);
              Navigator.pop(context);
              onCancelled();
              ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                content: Text('${item.title} berhasil dibatalkan'),
                backgroundColor: kRed,
                behavior: SnackBarBehavior.floating,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(10)),
              ));
            },
            child: const Text('Ya, Batalkan'),
          ),
        ],
      ),
    );
  }

  void _openDetail(BuildContext context) {
    Navigator.push(context,
        MaterialPageRoute(builder: (_) => DetailPengajuanPage(item: item)));
  }
}

// ─────────────────────────────────────────────
//  PROGRESS SECTION
// ─────────────────────────────────────────────
class _ProgressSection extends StatelessWidget {
  final List<ApprovalStep> steps;
  final PengajuanStatus status;
  const _ProgressSection({required this.steps, required this.status});

  Color get _bgColor {
    switch (status) {
      case PengajuanStatus.menunggu:
        return const Color(0xFFFFFBF0);
      case PengajuanStatus.disetujui:
        return const Color(0xFFF1FBF2);
      case PengajuanStatus.ditolak:
        return const Color(0xFFFFF5F5);
    }
  }

  Color get _borderColor {
    switch (status) {
      case PengajuanStatus.menunggu:
        return const Color(0xFFFAEAC0);
      case PengajuanStatus.disetujui:
        return const Color(0xFFC8E6C9);
      case PengajuanStatus.ditolak:
        return const Color(0xFFFFCDD2);
    }
  }

  Color get _labelColor {
    switch (status) {
      case PengajuanStatus.menunggu:
        return kOrange;
      case PengajuanStatus.disetujui:
        return kGreen;
      case PengajuanStatus.ditolak:
        return kRed;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 14),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: _bgColor,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: _borderColor),
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Text('PROGRES PERSETUJUAN',
            style: TextStyle(
                fontSize: 10,
                fontWeight: FontWeight.bold,
                color: _labelColor,
                letterSpacing: 0.5)),
        const SizedBox(height: 10),
        ...steps.map((s) => _StepRow(step: s)),
      ]),
    );
  }
}

class _StepRow extends StatelessWidget {
  final ApprovalStep step;
  const _StepRow({required this.step});

  @override
  Widget build(BuildContext context) {
    Widget dot;
    Color textColor;
    FontWeight fw;
    switch (step.state) {
      case StepState.done:
        dot = Container(
          width: 14, height: 14,
          decoration:
              const BoxDecoration(color: kGreen, shape: BoxShape.circle),
          child: const Icon(Icons.check, size: 9, color: Colors.white),
        );
        textColor = const Color(0xFF333333);
        fw = FontWeight.normal;
        break;
      case StepState.waiting:
        dot = Container(
          width: 14, height: 14,
          decoration: BoxDecoration(
              color: Colors.white,
              shape: BoxShape.circle,
              border: Border.all(color: kOrange, width: 2)),
          child: Center(
              child: Container(
                  width: 5,
                  height: 5,
                  decoration: const BoxDecoration(
                      color: kOrange, shape: BoxShape.circle))),
        );
        textColor = kOrange;
        fw = FontWeight.w600;
        break;
      case StepState.pending:
        dot = Container(
          width: 14, height: 14,
          decoration: BoxDecoration(
              color: Colors.white,
              shape: BoxShape.circle,
              border: Border.all(
                  color: const Color(0xFFCCCCCC), width: 2)),
        );
        textColor = const Color(0xFFAAAAAA);
        fw = FontWeight.normal;
        break;
    }
    final text = (step.time.isNotEmpty && step.time != '–')
        ? '${step.label}  ${step.time}'
        : step.label;
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(children: [
        dot,
        const SizedBox(width: 8),
        Expanded(
            child: Text(text,
                style: TextStyle(
                    fontSize: 12, color: textColor, fontWeight: fw))),
      ]),
    );
  }
}

// ─────────────────────────────────────────────
//  SMALL BUTTON
// ─────────────────────────────────────────────
class _SmallButton extends StatelessWidget {
  final String label;
  final Color textColor, borderColor, bgColor;
  final VoidCallback onPressed;
  const _SmallButton(
      {required this.label,
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

// ─────────────────────────────────────────────
//  DETAIL PAGE
// ─────────────────────────────────────────────
class DetailPengajuanPage extends StatelessWidget {
  final PengajuanItem item;
  const DetailPengajuanPage({super.key, required this.item});

  Color get _statusColor {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return kOrange;
      case PengajuanStatus.disetujui:
        return kGreen;
      case PengajuanStatus.ditolak:
        return kRed;
    }
  }

  Color get _statusBg {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return const Color(0xFFFFF3E0);
      case PengajuanStatus.disetujui:
        return const Color(0xFFE8F5E9);
      case PengajuanStatus.ditolak:
        return const Color(0xFFFFEBEE);
    }
  }

  String get _statusLabel {
    switch (item.status) {
      case PengajuanStatus.menunggu:
        return 'Menunggu';
      case PengajuanStatus.disetujui:
        return 'Disetujui';
      case PengajuanStatus.ditolak:
        return 'Ditolak';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kPrimary,
      body: SafeArea(
        child: Column(children: [
          // AppBar
          Padding(
            padding: const EdgeInsets.fromLTRB(12, 12, 20, 16),
            child: Row(children: [
              IconButton(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(Icons.arrow_back_ios_new_rounded,
                    color: Colors.white, size: 20),
              ),
              const Expanded(
                  child: Text('Detail Pengajuan',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: Colors.white,
                          fontSize: 18,
                          fontWeight: FontWeight.bold))),
              const SizedBox(width: 44),
            ]),
          ),
          // Body
          Expanded(
            child: Container(
              decoration: const BoxDecoration(
                color: kBg,
                borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(28),
                    topRight: Radius.circular(28)),
              ),
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Title card
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          boxShadow: [
                            BoxShadow(
                                color: Colors.black.withOpacity(0.04),
                                blurRadius: 8)
                          ],
                        ),
                        child: Row(children: [
                          Container(
                            width: 52, height: 52,
                            decoration: BoxDecoration(
                                color: item.emojiBg,
                                borderRadius: BorderRadius.circular(12)),
                            child: Center(
                                child: Text(item.emoji,
                                    style:
                                        const TextStyle(fontSize: 26))),
                          ),
                          const SizedBox(width: 14),
                          Expanded(
                              child: Column(
                                  crossAxisAlignment:
                                      CrossAxisAlignment.start,
                                  children: [
                                Text(item.title,
                                    style: const TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.bold,
                                        color: Color(0xFF1A1A2E))),
                                const SizedBox(height: 4),
                                Text(item.dateRange,
                                    style: const TextStyle(
                                        fontSize: 13,
                                        color: Color(0xFF888888))),
                              ])),
                          Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                                color: _statusBg,
                                borderRadius: BorderRadius.circular(8)),
                            child: Text(_statusLabel,
                                style: TextStyle(
                                    color: _statusColor,
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold)),
                          ),
                        ]),
                      ),
                      const SizedBox(height: 16),
                      // Info rows
                      _DetailCard(children: [
                        _infoRow(Icons.tag, 'ID Pengajuan', item.id),
                        _divider(),
                        _infoRow(Icons.calendar_today_outlined,
                            'Tanggal Diajukan', item.submittedDate),
                        _divider(),
                        _infoRow(Icons.note_outlined, 'Catatan',
                            item.catatan),
                      ]),
                      const SizedBox(height: 16),
                      const Text('PROGRES PERSETUJUAN',
                          style: TextStyle(
                              fontSize: 11,
                              fontWeight: FontWeight.bold,
                              color: Color(0xFF888888),
                              letterSpacing: 0.8)),
                      const SizedBox(height: 10),
                      _DetailCard(children: [
                        ...List.generate(item.steps.length, (i) {
                          final s = item.steps[i];
                          final isLast = i == item.steps.length - 1;
                          return _DetailStepRow(
                              step: s, isLast: isLast);
                        }),
                      ]),
                      const SizedBox(height: 24),
                    ]),
              ),
            ),
          ),
        ]),
      ),
    );
  }

  Widget _infoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
      child: Row(children: [
        Icon(icon, size: 18, color: kPrimary.withOpacity(0.6)),
        const SizedBox(width: 12),
        Expanded(
            child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
          Text(label,
              style: const TextStyle(
                  fontSize: 11, color: Color(0xFF888888))),
          const SizedBox(height: 2),
          Text(value,
              style: const TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: Color(0xFF1A1A2E))),
        ])),
      ]),
    );
  }

  Widget _divider() => const Divider(
      height: 1,
      indent: 16,
      endIndent: 16,
      color: Color(0xFFF0F0F0));
}

class _DetailCard extends StatelessWidget {
  final List<Widget> children;
  const _DetailCard({required this.children});
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04), blurRadius: 8)
        ],
      ),
      child: Column(children: children),
    );
  }
}

class _DetailStepRow extends StatelessWidget {
  final ApprovalStep step;
  final bool isLast;
  const _DetailStepRow({required this.step, required this.isLast});

  @override
  Widget build(BuildContext context) {
    Widget dot;
    Color textColor;
    switch (step.state) {
      case StepState.done:
        dot = Container(
          width: 16, height: 16,
          decoration:
              const BoxDecoration(color: kGreen, shape: BoxShape.circle),
          child: const Icon(Icons.check, size: 10, color: Colors.white),
        );
        textColor = const Color(0xFF333333);
        break;
      case StepState.waiting:
        dot = Container(
          width: 16, height: 16,
          decoration: BoxDecoration(
              color: Colors.white,
              shape: BoxShape.circle,
              border: Border.all(color: kOrange, width: 2)),
          child: Center(
              child: Container(
                  width: 6,
                  height: 6,
                  decoration: const BoxDecoration(
                      color: kOrange, shape: BoxShape.circle))),
        );
        textColor = kOrange;
        break;
      case StepState.pending:
        dot = Container(
          width: 16, height: 16,
          decoration: BoxDecoration(
              color: Colors.white,
              shape: BoxShape.circle,
              border: Border.all(
                  color: const Color(0xFFCCCCCC), width: 2)),
        );
        textColor = const Color(0xFFAAAAAA);
        break;
    }
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
      child: Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Column(children: [
          dot,
          if (!isLast)
            Container(
                width: 2,
                height: 28,
                color: const Color(0xFFE0E0E0)),
        ]),
        const SizedBox(width: 14),
        Expanded(
          child: Padding(
            padding: const EdgeInsets.only(bottom: 14),
            child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
              Text(step.label,
                  style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color: textColor)),
              if (step.time.isNotEmpty && step.time != '–') ...[
                const SizedBox(height: 2),
                Text(step.time,
                    style: const TextStyle(
                        fontSize: 12, color: Color(0xFF888888))),
              ],
            ]),
          ),
        ),
      ]),
    );
  }
}

// ─────────────────────────────────────────────
//  BUAT PENGAJUAN SHEET
// ─────────────────────────────────────────────
class BuatPengajuanSheet extends StatefulWidget {
  final Function(PengajuanItem) onSubmitted;
  const BuatPengajuanSheet({super.key, required this.onSubmitted});
  @override
  State<BuatPengajuanSheet> createState() =>
      _BuatPengajuanSheetState();
}

class _BuatPengajuanSheetState extends State<BuatPengajuanSheet> {
  String _jenis = 'Cuti Tahunan';
  final TextEditingController _tglMulai = TextEditingController();
  final TextEditingController _tglSelesai = TextEditingController();
  final TextEditingController _catatan = TextEditingController();

  final _jenisOptions = [
    'Cuti Tahunan',
    'Cuti Sakit',
    'WFH',
    'Lembur',
    'Izin Lainnya'
  ];

  String get _emoji {
    switch (_jenis) {
      case 'Cuti Tahunan':
        return '🏝️';
      case 'Cuti Sakit':
        return '🏥';
      case 'WFH':
        return '💻';
      case 'Lembur':
        return '📋';
      default:
        return '📄';
    }
  }

  Color get _emojiBg {
    switch (_jenis) {
      case 'Cuti Tahunan':
        return const Color(0xFFFFF3E0);
      case 'Cuti Sakit':
        return const Color(0xFFE3F2FD);
      case 'WFH':
        return const Color(0xFFEEEEEE);
      case 'Lembur':
        return const Color(0xFFF3E5F5);
      default:
        return const Color(0xFFE0F2F1);
    }
  }

  Future<void> _pickDate(TextEditingController ctrl) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2025),
      lastDate: DateTime(2027),
      builder: (ctx, child) => Theme(
        data: ThemeData(
            colorScheme:
                const ColorScheme.light(primary: kPrimary)),
        child: child!,
      ),
    );
    if (picked != null) {
      ctrl.text =
          '${picked.day} ${_monthName(picked.month)} ${picked.year}';
    }
  }

  String _monthName(int m) {
    const names = [
      '',
      'Jan','Feb','Mar','Apr','Mei','Jun',
      'Jul','Agu','Sep','Okt','Nov','Des'
    ];
    return names[m];
  }

  void _submit() {
    if (_tglMulai.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(
          content: Text('Tanggal mulai harus diisi'),
          backgroundColor: kRed,
          behavior: SnackBarBehavior.floating));
      return;
    }
    final now = DateTime.now();
    final dateRange = _tglSelesai.text.isEmpty
        ? '${_tglMulai.text} · 1 hari'
        : '${_tglMulai.text} – ${_tglSelesai.text}';
    final newItem = PengajuanItem(
      id: 'P${(globalData.length + 1).toString().padLeft(3, '0')}',
      emoji: _emoji,
      emojiBg: _emojiBg,
      title: 'Pengajuan $_jenis',
      dateRange: dateRange,
      submittedDate:
          '${now.day} ${_monthName(now.month)} ${now.year}',
      status: PengajuanStatus.menunggu,
      catatan:
          _catatan.text.isEmpty ? '-' : _catatan.text,
      steps: [
        ApprovalStep(
          label: 'Diajukan oleh karyawan',
          time:
              '${now.day} ${_monthName(now.month)}, ${now.hour.toString().padLeft(2, '0')}:${now.minute.toString().padLeft(2, '0')}',
          state: StepState.done,
        ),
        const ApprovalStep(
            label: 'Menunggu persetujuan Admin',
            time: '–',
            state: StepState.waiting),
        const ApprovalStep(
            label: 'Keputusan final',
            time: '–',
            state: StepState.pending),
      ],
    );
    widget.onSubmitted(newItem);
    Navigator.pop(context);
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text('Pengajuan $_jenis berhasil diajukan!'),
      backgroundColor: kGreen,
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10)),
    ));
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.85,
      minChildSize: 0.5,
      maxChildSize: 0.95,
      builder: (_, scrollCtrl) => Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius:
              BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: Column(children: [
          const SizedBox(height: 10),
          Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                  color: const Color(0xFFDDDDDD),
                  borderRadius: BorderRadius.circular(2))),
          const SizedBox(height: 16),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 20),
            child: Text('Buat Pengajuan Baru',
                style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF1A1A2E))),
          ),
          const SizedBox(height: 4),
          const Text('Isi formulir di bawah ini',
              style: TextStyle(
                  fontSize: 13, color: Color(0xFF888888))),
          const Divider(height: 24),
          Expanded(
            child: ListView(
                controller: scrollCtrl,
                padding:
                    const EdgeInsets.fromLTRB(20, 0, 20, 24),
                children: [
                  _fieldLabel('Jenis Pengajuan'),
                  const SizedBox(height: 8),
                  Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 14),
                    decoration: BoxDecoration(
                      color: kBg,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(
                          color: const Color(0xFFE0E0E0)),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton<String>(
                        value: _jenis,
                        isExpanded: true,
                        icon: const Icon(
                            Icons.keyboard_arrow_down_rounded,
                            color: kPrimary),
                        items: _jenisOptions
                            .map((e) => DropdownMenuItem(
                                value: e, child: Text(e)))
                            .toList(),
                        onChanged: (v) =>
                            setState(() => _jenis = v!),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  _fieldLabel('Tanggal Mulai *'),
                  const SizedBox(height: 8),
                  _dateField(
                      _tglMulai, 'Pilih tanggal mulai'),
                  const SizedBox(height: 16),
                  _fieldLabel('Tanggal Selesai'),
                  const SizedBox(height: 8),
                  _dateField(_tglSelesai,
                      'Pilih tanggal selesai (opsional)'),
                  const SizedBox(height: 16),
                  _fieldLabel('Catatan'),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _catatan,
                    maxLines: 3,
                    decoration: InputDecoration(
                      hintText:
                          'Tambahkan keterangan atau alasan...',
                      hintStyle: const TextStyle(
                          color: Color(0xFFAAAAAA), fontSize: 13),
                      filled: true,
                      fillColor: kBg,
                      border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: const BorderSide(
                              color: Color(0xFFE0E0E0))),
                      enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: const BorderSide(
                              color: Color(0xFFE0E0E0))),
                      focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: const BorderSide(
                              color: kPrimary, width: 1.5)),
                      contentPadding: const EdgeInsets.all(14),
                    ),
                  ),
                  const SizedBox(height: 28),
                  SizedBox(
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: kPrimary,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(14)),
                        elevation: 0,
                      ),
                      child: const Text('Ajukan Sekarang',
                          style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold)),
                    ),
                  ),
                ]),
          ),
        ]),
      ),
    );
  }

  Widget _fieldLabel(String text) {
    return Text(text,
        style: const TextStyle(
            fontSize: 13,
            fontWeight: FontWeight.w600,
            color: Color(0xFF1A1A2E)));
  }

  Widget _dateField(
      TextEditingController ctrl, String hint) {
    return GestureDetector(
      onTap: () => _pickDate(ctrl),
      child: AbsorbPointer(
        child: TextField(
          controller: ctrl,
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: const TextStyle(
                color: Color(0xFFAAAAAA), fontSize: 13),
            filled: true,
            fillColor: kBg,
            prefixIcon: const Icon(
                Icons.calendar_today_outlined,
                size: 18,
                color: kPrimary),
            border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: const BorderSide(
                    color: Color(0xFFE0E0E0))),
            enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: const BorderSide(
                    color: Color(0xFFE0E0E0))),
            focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: const BorderSide(
                    color: kPrimary, width: 1.5)),
            contentPadding: const EdgeInsets.symmetric(
                vertical: 14, horizontal: 14),
          ),
        ),
      ),
    );
  }
}