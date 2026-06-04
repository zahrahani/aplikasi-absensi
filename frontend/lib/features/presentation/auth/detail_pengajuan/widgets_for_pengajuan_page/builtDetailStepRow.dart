import 'package:flutter/material.dart' hide StepState;
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';

class builtDetailStepRow extends StatelessWidget {
  final ApprovalStep step;
  final bool isLast;
  const builtDetailStepRow({required this.step, required this.isLast});

  @override
  Widget build(BuildContext context) {
    Widget dot;
    Color textColor;
    switch (step.state) {
      case StepState.done:
        dot = Container(
          width: 16, height: 16,
          decoration:
              const BoxDecoration(color: AppColors.successColor, shape: BoxShape.circle),
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
              border: Border.all(color: AppColors.warningColor, width: 2)),
          child: Center(
              child: Container(
                  width: 6,
                  height: 6,
                  decoration: const BoxDecoration(
                      color: AppColors.warningColor, shape: BoxShape.circle))),
        );
        textColor = AppColors.warningColor;
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