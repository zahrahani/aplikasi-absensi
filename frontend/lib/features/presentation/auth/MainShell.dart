import 'package:flutter/material.dart';
// rimport 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/dashboard/DashboardScreen.dart';
import 'package:presensi/features/presentation/auth/profile/ProfileScreen.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/RekapLaporanScreen.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/StatusPengajuanScreen.dart';
import 'package:presensi/features/widgets/bottomNavigation.dart';

class MainShell extends StatefulWidget {
  const MainShell({super.key});
  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _navIndex = 0;
 
  @override
  Widget build(BuildContext context) {
    final pages = [
      DashboardScreen(currentIndex: _navIndex),
      RekapLaporanScreen(currentIndex: _navIndex),
      StatusPengajuanScreen(currentIndex: _navIndex),
      ProfileScreen(currentIndex: _navIndex)
    ];
    return SafeArea(
      child: Scaffold(
        body: IndexedStack(index: _navIndex, children: pages),
        bottomNavigationBar: BottomNav(
          selectedIndex: _navIndex,
          onTap: (i) => setState(() => _navIndex = i),
        ),
      ),
    );
  }
}

// class _PlaceholderPage extends StatelessWidget {
//   final IconData icon;
//   final String label;
//   const _PlaceholderPage({required this.icon, required this.label});
//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       backgroundColor: AppColors.whiteBackground,
//       body: Center(
//         child: Column(mainAxisSize: MainAxisSize.min, children: [
//           Icon(icon, size: 64, color: AppColors.primaryPurple.withOpacity(0.3)),
//           const SizedBox(height: 12),
//           Text('Halaman $label',
//               style:
//                   TextStyle(fontSize: 18, color: AppColors.primaryPurple.withOpacity(0.5))),
//         ]),
//       ),
//     );
//   }
// }