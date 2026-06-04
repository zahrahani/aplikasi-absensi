import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildStateBadge.dart';

Widget buildStatsRow({
  required int hadir,
  required int telat,
  required int izin,
  required int sakit,
  required int alpha,
}) {
  final stats = [
    {'label': 'Hadir', 'value': hadir.toString(), 'color': AppColors.successColor},
    {'label': 'Telat', 'value': telat.toString(), 'color': AppColors.purpleColor},
    {'label': 'Izin',  'value': izin.toString(),  'color': AppColors.blurCircleColor},
    {'label': 'Sakit', 'value': sakit.toString(), 'color': AppColors.secondaryColor},
    {'label': 'Alpha', 'value': alpha.toString(), 'color': AppColors.dangerColor},
  ];

  return Padding(
    padding: const EdgeInsets.symmetric(horizontal: 16),
    child: Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: stats
          .map(
            (s) => buildStatBadge(
              s['value'] as String,
              s['label'] as String,
              s['color'] as Color,
            ),
          )
          .toList(),
    ),
  );
}