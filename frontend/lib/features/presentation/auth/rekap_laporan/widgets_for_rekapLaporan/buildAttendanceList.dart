// Widget: untuk membuat list Attendance pada dashboard
import 'package:flutter/material.dart';
import 'package:presensi/features/presentation/auth/rekap_laporan/widgets_for_rekapLaporan/buildAttendanceCard.dart';

Widget buildAttendanceList({
    required Map<String, List<Map<String, dynamic>>> attendanceData
  }) {

     return Column(
    children: attendanceData.entries.map((weekEntry) {

      // KEY
      final weekName = weekEntry.key;

      // VALUE
      final weekData = weekEntry.value;


      return Column(
        children: [

          // TITLE MINGGU
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Align(
              alignment: Alignment.centerLeft,
              child: Text(
                weekName,
                style: TextStyle(
                  fontSize: 13,
                  color: Colors.grey[600],
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ),

          const SizedBox(height: 8),

          // LOOP DATA ABSENSI
          ...weekData.map<Widget>((Map<String, dynamic> data) {
            return buildAttendanceCard(data);
          }),

        ],
      );
    }).toList(),
  );
}