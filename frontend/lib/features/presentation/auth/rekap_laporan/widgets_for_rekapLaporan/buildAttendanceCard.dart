
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildAttendanceCard(Map<String, dynamic> data) {

  final String status = data['status'];

  // =========================
  // STATUS COLOR
  // =========================
  Color statusColor;
  IconData statusIcon;

  switch (status) {

    case 'Hadir':
      statusColor = AppColors.successColor;
      statusIcon = Icons.check;
      break;

    case 'Telat':
      statusColor = AppColors.purpleColor;
      statusIcon = Icons.check;
      break;
    case 'Izin':
      statusColor = AppColors.blurCircleColor;
      statusIcon = Icons.close;
      break;

    case 'Sakit':
      statusColor = AppColors.secondaryColor;
      statusIcon = Icons.close;
      break;

    case 'Alpha':
      statusColor = AppColors.dangerColor;
      statusIcon = Icons.close;
      break;

    default:
      statusColor = Colors.grey;
      statusIcon = Icons.help_outline;
  }

  return Container(
    margin: const EdgeInsets.fromLTRB(16, 0, 16, 10),
    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),

    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(14),
      boxShadow: [
        BoxShadow(
          color: Colors.black.withOpacity(0.05),
          blurRadius: 8,
          offset: const Offset(0, 3),
        ),
      ],
    ),

    child: Row(
      children: [

        // =========================
        // DAY NUMBER
        // =========================
        SizedBox(
          width: 52,

          child: Column(
            children: [
              Text(
                data['day'],
                style: const TextStyle(
                  fontSize: 26,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF1A1A2E),
                  height: 1,
                ),
              ),

              Text(
                data['dayName'],
                style: TextStyle(
                  fontSize: 13,
                  color: Colors.grey[500],
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ),
        ),

        // DIVIDER
        Container(
          width: 1,
          height: 50,
          color: Colors.grey.shade200,
          margin: const EdgeInsets.symmetric(horizontal: 14),
        ),

        // =========================
        // TIME INFO
        // =========================
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,

            children: [

              Row(
                children: [
                  Icon(
                    Icons.access_time_rounded,
                    size: 14,
                    color: Colors.grey[500],
                  ),

                  const SizedBox(width: 4),

                  Text(
                    'Masuk : ${data['masuk']}',
                    style: const TextStyle(
                      fontSize: 13,
                      color: Color(0xFF1A1A2E),
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 6),

              Row(
                children: [
                  Icon(
                    Icons.access_time_rounded,
                    size: 14,
                    color: Colors.grey,
                  ),

                  const SizedBox(width: 4),

                  Text(
                    'Pulang : ${data['pulang']}',
                    style: const TextStyle(
                      fontSize: 13,
                      color: Color(0xFF1A1A2E),
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),

        // =========================
        // STATUS BADGE
        // =========================
        Column(
          crossAxisAlignment: CrossAxisAlignment.end,

          children: [

            Container(
              padding: const EdgeInsets.symmetric(
                horizontal: 12,
                vertical: 5,
              ),

              decoration: BoxDecoration(
                color: statusColor,
                borderRadius: BorderRadius.circular(8),
              ),

              child: Row(
                mainAxisSize: MainAxisSize.min,

                children: [

                  Icon(
                    statusIcon,
                    color: Colors.white,
                    size: 12,
                  ),

                  const SizedBox(width: 3),

                  Text(
                    status,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 6),

            Container(
              padding: const EdgeInsets.symmetric(
                horizontal: 12,
                vertical: 5,
              ),

              decoration: BoxDecoration(
                color: Colors.grey.shade200,
                borderRadius: BorderRadius.circular(8),
              ),

              child: Text(
                data['duration'],
                style: const TextStyle(
                  color: Color(0xFF1A1A2E),
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ],
        ),
      ],
    ),
  );
}