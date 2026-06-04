import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:presensi/core/theme/app_colors.dart';

Widget buildHeader({
  required String judulScreen,
  required String nama,
  required String jabatan,
  required String divisi,
  String? fotoProfil,
}) {

  final webEndpoint = dotenv.env['WEB_ENDPOINT'];

  return Container(
    width: double.infinity,
    color: AppColors.primaryPurple,
    padding: const EdgeInsets.only(
      top: 20,
      left: 20,
      right: 20,
      bottom: 48,
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [

        Text(
          judulScreen,
          style: const TextStyle(
            color: Colors.white,
            fontSize: 20,
            fontWeight: FontWeight.bold,
          ),
        ),

        const SizedBox(height: 28),

        Row(
          children: [

            CircleAvatar(
              radius: 26,
              backgroundColor: Colors.white.withOpacity(0.15),

              backgroundImage: fotoProfil != null &&
                      fotoProfil.isNotEmpty
                  ? NetworkImage(
                      '$webEndpoint/$fotoProfil',
                    )
                  : null,

              child: fotoProfil == null ||
                      fotoProfil.isEmpty
                  ? const Icon(
                      Icons.person_outline,
                      color: Colors.white,
                      size: 30,
                    )
                  : null,
            ),

            const SizedBox(width: 12),

            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [

                  Text(
                    'Halo, $nama',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 17,
                      fontWeight: FontWeight.bold,
                    ),
                    overflow: TextOverflow.ellipsis,
                    maxLines: 1,
                  ),

                  const SizedBox(height: 2),

                  Text(
                    '$divisi | $jabatan',
                    style: const TextStyle(
                      color: Colors.white70,
                      fontSize: 15,
                    ),
                    overflow: TextOverflow.ellipsis,
                    maxLines: 1,
                  ),
                ],
              ),
            ),
          ],
        ),
      ],
    ),
  );
}