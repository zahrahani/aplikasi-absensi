import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/buatPengajuanCard.dart';

void buildShowBuatPengajuanSheet({
  required BuildContext context,
  required WidgetRef ref,
  required VoidCallback onSubmitted,
}) {
  showModalBottomSheet(
    context           : context,
    isScrollControlled: true,
    backgroundColor   : Colors.transparent,
    builder: (_) => BuatPengajuanSheet(
      ref        : ref,
      onSubmitted: onSubmitted,
    ),
  );
}