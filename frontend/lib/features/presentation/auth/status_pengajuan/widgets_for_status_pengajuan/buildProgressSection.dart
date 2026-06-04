import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/widgets_for_status_pengajuan/buildStepRow.dart';

class buildProgressSection extends StatelessWidget {
  final List<ApprovalStep> steps;
  final PengajuanStatus status;
  const buildProgressSection({required this.steps, required this.status});

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
        return AppColors.warningColor;
      case PengajuanStatus.disetujui:
        return AppColors.successColor;
      case PengajuanStatus.ditolak:
        return AppColors.dangerColor;
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
        ...steps.map((s) => buildStepRow(step: s)),
      ]),
    );
  }
}