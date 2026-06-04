import 'package:flutter/material.dart'  hide StepState;
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';

class buildStepRow extends StatelessWidget {
  final ApprovalStep step;
  const buildStepRow({required this.step});

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
              const BoxDecoration(color: AppColors.successColor, shape: BoxShape.circle),
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
              border: Border.all(color: AppColors.warningColor, width: 2)),
          child: Center(
              child: Container(
                  width: 5,
                  height: 5,
                  decoration: const BoxDecoration(
                      color: AppColors.warningColor, shape: BoxShape.circle))),
        );
        textColor = AppColors.warningColor;
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