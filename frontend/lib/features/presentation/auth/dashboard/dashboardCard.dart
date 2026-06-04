import 'package:flutter/material.dart';
import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildMontlyAttendanceCard.dart';
import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildShiftCard.dart';
// import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildAttendanceList.dart';
// import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildMonthPickerWidgetState.dart';
// import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildStateBadge.dart';
import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildTimeCard.dart';
import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildTodayAttendanceCard.dart';
// import 'package:presensi/features/presentation/auth/dashboard/widgets_for_dashboard/buildTotalWorkHours.dart';


class DashboardCard extends StatelessWidget {
  final Map homeData; // ← terima dari DashboardScreen
  final Future<void> Function() onRefresh;

  const DashboardCard({
    super.key,
    required this.homeData,
    required this.onRefresh
  });

  @override
  Widget build(BuildContext context) {
    final absensi = homeData['absensi_hari_ini'];
    final rekap   = homeData['rekap_bulan_ini'];
    final shift = homeData['shift'];
    
    
    return Expanded(
      child: RefreshIndicator(
        onRefresh: () async {}, // ← refresh dari parent, kosongkan dulu
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            children: [
              buildTimeCard(
                context: context,
                status : homeData['status_absen'],
              ),
              const SizedBox(height: 12),

              buildTodayAttendanceCard(
                checkIn      : absensi['jam_masuk']  ?? '--:--',
                checkOut     : absensi['jam_pulang'] ?? '--:--',
                checkInStatus: absensi['status']     ?? '-',
                duration     : absensi['durasi']     ?? '-',
              ),
              const SizedBox(height: 12),
      
              buildShiftCard(
                namaShift  : shift['nama']        ?? '-',
                jamMasuk   : shift['jam_masuk']   ?? '--:--',
                jamPulang  : shift['jam_pulang']  ?? '--:--',
                batasTelat : shift['batas_telat'] ?? '--:--',
              ),
              const SizedBox(height: 12),


              buildMonthlyAttendanceCard(
                hadir         : rekap['hadir'],
                telat         : rekap['telat'],
                izin          : rekap['izin'],
                sakit         : rekap['sakit'],
                alpha         : rekap['alpha'],
                totalHariKerja: rekap['total_hari_kerja'],
              ),
            ],
          ),
        ),
      ),
    );
  }
}