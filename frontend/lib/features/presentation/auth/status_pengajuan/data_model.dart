// import 'package:flutter/material.dart';

enum PengajuanStatus { menunggu, disetujui, ditolak }
enum StepState { done, waiting, pending }

class ApprovalStep {
  final String label;
  final String time;
  final StepState state;
  const ApprovalStep({
    required this.label,
    required this.time,
    required this.state,
  });
}

class PengajuanItem {
  final String pengajuanId;
  final String jenisId;
  final String namaJenis;
  final String iconName;
  final String tanggalMulai;
  final String tanggalSelesai;
  final String alasan;
  final String submittedDate;
  final bool isUrgent;
  PengajuanStatus status;
  final List<ApprovalStep> steps;
  final String catatanAdmin;

  PengajuanItem({
    required this.pengajuanId,
    required this.jenisId,
    required this.namaJenis,
    required this.iconName,
    required this.tanggalMulai,
    required this.tanggalSelesai,
    required this.alasan,
    required this.submittedDate,
    required this.isUrgent,
    required this.status,
    required this.steps,
    this.catatanAdmin = '-',
  });

  // ── Parse dari JSON server ─────────────────────────────
  factory PengajuanItem.fromJson(Map<String, dynamic> json) {
    // Parse status
    PengajuanStatus status;
    switch (json['status_pengajuan']) {
      case 'approved':
        status = PengajuanStatus.disetujui;
        break;
      case 'rejected':
        status = PengajuanStatus.ditolak;
        break;
      default:
        status = PengajuanStatus.menunggu;
    }

    // Parse approval steps dari history
    final List<dynamic> historyRaw = json['approval_history'] ?? [];
    final List<ApprovalStep> steps = _buildSteps(historyRaw, status);

    return PengajuanItem(
      pengajuanId  : json['pengajuan_id'].toString(),
      jenisId      : json['jenis_id']       ?? '',
      namaJenis    : json['nama_jenis']      ?? '-',
      iconName     : json['icon_name']       ?? 'description',
      tanggalMulai : json['tanggal_mulai']   ?? '-',
      tanggalSelesai: json['tanggal_selesai'] ?? '-',
      alasan       : json['alasan']          ?? '-',
      submittedDate: json['created_at']      ?? '-',
      isUrgent     : (json['is_urgent'] ?? 0) == 1,
      status       : status,
      steps        : steps,
      catatanAdmin : json['catatan_admin']   ?? '-',
    );
  }

  static List<ApprovalStep> _buildSteps(
    List<dynamic> history,
    PengajuanStatus status,
  ) {
    // Step 1 — selalu done (sudah diajukan)
    final created = history.firstWhere(
      (h) => h['action_type'] == 'created',
      orElse: () => null,
    );

    // Step 2 — admin review
    final reviewed = history.firstWhere(
      (h) => h['action_type'] == 'approved' || h['action_type'] == 'rejected',
      orElse: () => null,
    );

    return [
      ApprovalStep(
        label: 'Diajukan oleh karyawan',
        time : created != null ? created['action_time'] ?? '-' : '-',
        state: StepState.done,
      ),
      ApprovalStep(
        label: 'Menunggu persetujuan Admin',
        time : reviewed != null ? reviewed['action_time'] ?? '-' : '-',
        state: status == PengajuanStatus.menunggu
            ? StepState.waiting
            : StepState.done,
      ),
      ApprovalStep(
        label: status == PengajuanStatus.disetujui
            ? 'Disetujui'
            : status == PengajuanStatus.ditolak
                ? 'Ditolak'
                : 'Keputusan final',
        time : reviewed != null ? reviewed['action_time'] ?? '-' : '-',
        state: status == PengajuanStatus.menunggu
            ? StepState.pending
            : StepState.done,
      ),
    ];
  }
}