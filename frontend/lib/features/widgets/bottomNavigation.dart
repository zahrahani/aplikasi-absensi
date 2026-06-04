import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

class BottomNav extends StatelessWidget {
  final int selectedIndex;
  final ValueChanged<int> onTap;
  const BottomNav({required this.selectedIndex, required this.onTap});

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
                        color: active ? AppColors.primaryPurple : const Color(0xFFAAAAAA),
                        size: 24),
                    const SizedBox(height: 3),
                    Text(items[i].$2,
                        style: TextStyle(
                          fontSize: 11,
                          color: active ? AppColors.primaryPurple : const Color(0xFFAAAAAA),
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