import 'package:flutter/material.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildAttendanceList.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildMontlyPickerWidgetState.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildStatsRow.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildTotalWorkHours.dart';

class RekapLaporanCard extends StatelessWidget {
  final Map rekapData;
  final int selectedMonth;
  final int selectedYear;
  final Future<void> Function() onRefresh;
  final Function(int month, int year) onMonthChanged;

  const RekapLaporanCard({
    super.key,
    required this.rekapData,
    required this.onRefresh,
    required this.onMonthChanged,
    required this.selectedMonth,
    required this.selectedYear,
  });

  @override
  Widget build(BuildContext context) {
    final rekap        = rekapData['rekap'];
    final rawAttendance = rekapData['attendance_data'];

    final Map<String, dynamic> rawData;

    if (rawAttendance == null || rawAttendance is List) {
      // kosong atau salah tipe pakai map kosong
      rawData = {};
    } else {
      rawData = Map<String, dynamic>.from(rawAttendance);
    }

    final attendanceData = rawData.map((key, value) {
      final list = (value as List).map((item) {
        return Map<String, dynamic>.from(item);
      }).toList();
      return MapEntry(key, list);
    });

    return Expanded(
      child: RefreshIndicator(
        onRefresh: onRefresh,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            children: [
              const SizedBox(height: 12),

              // ── Pilih bulan ───────────────────────────────
              buildMontlyPickerWidgetState(
                selectedMonth: selectedMonth,
                selectedYear: selectedYear,
                onMonthChanged: onMonthChanged,
              ),
              const SizedBox(height: 12),

              // ── Stats ─────────────────────────────────────
              buildStatsRow(
                hadir: rekap['hadir'],
                telat: rekap['telat'],
                izin : rekap['izin'],
                sakit: rekap['sakit'],
                alpha: rekap['alpha'],
              ),
              const SizedBox(height: 12),

              // ── Total jam kerja ───────────────────────────
              buildTotalWorkHours(
                jam: rekapData['total_jam_kerja'],
              ),
              const SizedBox(height: 16),

              // ── List absensi per minggu ───────────────────
              buildAttendanceList(
                attendanceData: attendanceData,
              ),
            ],
          ),
        ),
      ),
    );
  }
}