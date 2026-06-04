// import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
// import 'package:image_picker/image_picker.dart';
// import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/profile/ProfileController.dart';
import 'package:presensi/features/presentation/auth/profile/widgets_for_profile/buildEditProfileSheet.dart';
import 'package:presensi/features/presentation/auth/profile/widgets_for_profile/buildGantiPasswordSheet.dart';
import 'package:presensi/features/presentation/auth/profile/widgets_for_profile/buildInfoItem.dart';
import 'package:presensi/features/presentation/auth/profile/widgets_for_profile/buildSectionCard.dart';
import 'package:presensi/features/widgets/showAlert.dart';
import 'package:file_picker/file_picker.dart';

class ProfileScreen extends ConsumerStatefulWidget {
  final int currentIndex;

  const ProfileScreen({super.key, required this.currentIndex});

  @override
  ConsumerState<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends ConsumerState<ProfileScreen> {
  Map?    profileData;
  bool    isLoading   = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  @override
  void didUpdateWidget(covariant ProfileScreen oldWidget) {
    super.didUpdateWidget(oldWidget);

    if (widget.currentIndex == 3 &&
        oldWidget.currentIndex != 3) {

      _loadProfile();
      
    }
  }

  Future<void> _loadProfile() async {
    setState(() {
      isLoading    = true;
      errorMessage = null;
    });



    final result = await ProfileController.getProfile(ref);

    if (!mounted) return;

    setState(() {
      isLoading = false;
      if (result['success']) {
        profileData = result['data'];
      } else {
        errorMessage = result['messages'];
      }
    });
  }

  // ── Pilih & upload foto ───────────────────────────────────

Future<void> _pickFoto() async {
  try {
    final result = await FilePicker.platform.pickFiles(
      type           : FileType.image,
      allowMultiple  : false,
    );

    if (result == null || result.files.isEmpty) return;

    final path = result.files.single.path;
    if (path == null) return;

    final uploadResult = await ProfileController.updateFoto(
      ref : ref,
      foto: File(path),
    );

    if (!mounted) return;

    showAlert(
      context    : context,
      message    : uploadResult['messages'],
      alertStatus: uploadResult['success'] ? 'success' : 'danger',
    );

    if (uploadResult['success']) _loadProfile();

  } catch (e) {
    print('Pick foto error: $e');
    showAlert(
      context    : context,
      message    : 'Gagal membuka galeri',
      alertStatus: 'danger',
    );
  }
}

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF0F0F5),
      body: Column(
        children: [
          // ── Header purple ─────────────────────────────────
          _buildHeader(),

          // ── Body ──────────────────────────────────────────
          Expanded(
            child: isLoading
                ? const Center(child: CircularProgressIndicator())
                : errorMessage != null
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(errorMessage!),
                            const SizedBox(height: 12),
                            ElevatedButton(
                              onPressed: _loadProfile,
                              child: const Text('Coba lagi'),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _loadProfile,
                        child: SingleChildScrollView(
                          physics: const AlwaysScrollableScrollPhysics(),
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            children: [
                              const SizedBox(height: 12),

                              // ── Info akun ──────────────
                              buildSectionCard(
                                title   : 'Informasi Akun',
                                children: [
                                  buildInfoItem(
                                    icon : Icons.badge_outlined,
                                    label: 'ID Karyawan',
                                    value: profileData!['user_id'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.person_outline,
                                    label: 'Username',
                                    value: profileData!['username'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.email_outlined,
                                    label: 'Email',
                                    value: profileData!['email'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.work_outline,
                                    label: 'Role',
                                    value: profileData!['role'] ?? '-',
                                    isLast: true,
                                  ),
                                ],
                              ),
                              const SizedBox(height: 12),

                              // ── Info karyawan ───────────
                              buildSectionCard(
                                title   : 'Informasi Karyawan',
                                children: [
                                  buildInfoItem(
                                    icon : Icons.business_outlined,
                                    label: 'Divisi',
                                    value: profileData!['nama_divisi'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.assignment_ind_outlined,
                                    label: 'Jabatan',
                                    value: profileData!['nama_jabatan'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.phone_outlined,
                                    label: 'No. Handphone',
                                    value: profileData!['no_handphone'] ?? '-',
                                  ),
                                  buildInfoItem(
                                    icon : Icons.location_on_outlined,
                                    label: 'Alamat',
                                    value: profileData!['alamat'] ?? '-',
                                    isLast: true,
                                  ),
                                ],
                              ),
                              const SizedBox(height: 12),

                              // ── Aksi ────────────────────
                              buildSectionCard(
                                title   : 'Pengaturan',
                                children: [
                                  _buildActionItem(
                                    icon    : Icons.edit_outlined,
                                    label   : 'Edit Profil',
                                    onTap   : _openEditProfile,
                                  ),
                                  _buildActionItem(
                                    icon    : Icons.lock_outline,
                                    label   : 'Ganti Password',
                                    onTap   : _openGantiPassword,
                                  ),
                                  _buildActionItem(
                                    icon    : Icons.logout_rounded,
                                    label   : 'Keluar',
                                    color   : AppColors.dangerColor,
                                    onTap   : _confirmLogout,
                                    isLast  : true,
                                  ),
                                ],
                              ),
                              const SizedBox(height: 24),
                            ],
                          ),
                        ),
                      ),
          ),
        ],
      ),
    );
  }

  // ── Header dengan foto profil ─────────────────────────────
  Widget _buildHeader() {
    final webEndpoint = dotenv.env['WEB_ENDPOINT'];

    final fotoUrl = profileData?['foto_profil'];
    final nama    = profileData?['nama_lengkap'] ?? '-';
    final jabatan = profileData?['nama_jabatan'] ?? '-';


    return Container(
      width  : double.infinity,
      color  : AppColors.primaryPurple,
      padding: const EdgeInsets.only(top: 20, left: 20, right: 20, bottom: 60),
      child  : Column(
        children: [
          // Foto profil
          Stack(
            children: [
              CircleAvatar(
                radius         : 42,
                backgroundColor: Colors.white.withOpacity(0.2),
                backgroundImage: fotoUrl != null
                    ? NetworkImage('$webEndpoint/$fotoUrl')
                    : null,
                child: fotoUrl == null
                    ? const Icon(Icons.person, color: Colors.white, size: 44)
                    : null,
              ),
              Positioned(
                bottom: 0,
                right : 0,
                child : GestureDetector(
                  onTap: _pickFoto,
                  child: Container(
                    padding   : const EdgeInsets.all(6),
                    decoration: const BoxDecoration(
                      color    : Colors.white,
                      shape    : BoxShape.circle,
                    ),
                    child: const Icon(
                      Icons.camera_alt_outlined,
                      size : 16,
                      color: AppColors.primaryPurple,
                    ),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            nama,
            style: const TextStyle(
              color     : Colors.white,
              fontSize  : 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            jabatan,
            style: TextStyle(
              color   : Colors.white.withOpacity(0.8),
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActionItem({
    required IconData icon,
    required String   label,
    required VoidCallback onTap,
    Color?  color,
    bool    isLast = false,
  }) {
    final itemColor = color ?? const Color(0xFF1A1A2E);
    return Column(
      children: [
        InkWell(
          onTap         : onTap,
          borderRadius  : BorderRadius.circular(8),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            child: Row(
              children: [
                Icon(icon, size: 20, color: itemColor),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    label,
                    style: TextStyle(
                      fontSize  : 14,
                      fontWeight: FontWeight.w500,
                      color     : itemColor,
                    ),
                  ),
                ),
                Icon(
                  Icons.chevron_right_rounded,
                  color: Colors.grey[400],
                  size : 20,
                ),
              ],
            ),
          ),
        ),
        if (!isLast)
          const Divider(height: 1, indent: 48, color: Color(0xFFF0F0F0)),
      ],
    );
  }

  void _openEditProfile() {
    showModalBottomSheet(
      context           : context,
      isScrollControlled: true,
      backgroundColor   : Colors.transparent,
      builder: (_) => BuildEditProfileSheet(
        profileData: profileData!,
        ref        : ref,
        onUpdated  : _loadProfile,
      ),
    );
  }

  void _openGantiPassword() {
    showModalBottomSheet(
      context           : context,
      isScrollControlled: true,
      backgroundColor   : Colors.transparent,
      builder: (_) => BuildGantiPasswordSheet(
        ref      : ref,
        onUpdated: _loadProfile,
      ),
    );
  }

 void _confirmLogout() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text(
          'Keluar',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        content: const Text('Apakah Anda yakin ingin keluar?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal',
                style: TextStyle(color: Color(0xFF888888))),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.dangerColor,
              foregroundColor: Colors.white,
              elevation      : 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
            onPressed: () async {
              Navigator.pop(context); // tutup dialog dulu

              // Panggil logout — hapus token di server + shared preferences
              await ProfileController.logout(ref);

              if (!mounted) return;
              Navigator.pushReplacementNamed(context, '/login');
            },
            child: const Text('Ya, Keluar'),
          ),
        ],
      ),
    );
  }
}