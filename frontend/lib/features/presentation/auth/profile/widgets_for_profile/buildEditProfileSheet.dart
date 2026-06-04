import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/profile/profileController.dart';
import 'package:presensi/features/widgets/InputField.dart';
import 'package:presensi/features/widgets/showAlert.dart';

class BuildEditProfileSheet extends StatefulWidget {
  final Map          profileData;
  final WidgetRef    ref;
  final VoidCallback onUpdated;

  const BuildEditProfileSheet({
    super.key,
    required this.profileData,
    required this.ref,
    required this.onUpdated,
  });

  @override
  State<BuildEditProfileSheet> createState() => _BuildEditProfileSheetState();
}

class _BuildEditProfileSheetState extends State<BuildEditProfileSheet> {
  late final TextEditingController _namaCtrl;
  late final TextEditingController _usernameCtrl;
  late final TextEditingController _emailCtrl;
  late final TextEditingController _hpCtrl;
  late final TextEditingController _alamatCtrl;

  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _namaCtrl     = TextEditingController(text: widget.profileData['nama_lengkap'] ?? '');
    _usernameCtrl = TextEditingController(text: widget.profileData['username']     ?? '');
    _emailCtrl    = TextEditingController(text: widget.profileData['email']        ?? '');
    _hpCtrl       = TextEditingController(text: widget.profileData['no_handphone'] ?? '');
    _alamatCtrl   = TextEditingController(text: widget.profileData['alamat']       ?? '');
  }

  @override
  void dispose() {
    _namaCtrl.dispose();
    _usernameCtrl.dispose();
    _emailCtrl.dispose();
    _hpCtrl.dispose();
    _alamatCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    setState(() => _isLoading = true);

    final result = await ProfileController.updateProfile(
      ref         : widget.ref,
      namaLengkap : _namaCtrl.text.trim(),
      username    : _usernameCtrl.text.trim(),
      email       : _emailCtrl.text.trim(),
      noHandphone : _hpCtrl.text.trim(),
      alamat      : _alamatCtrl.text.trim(),
    );

    if (!mounted) return;
    setState(() => _isLoading = false);

    

      Navigator.pop(context);
      widget.onUpdated();

    showAlert(
      context    : context,
      message    : result['messages'],
      alertStatus: result['success'] ? 'success' : 'danger',
    );
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.85,
      minChildSize    : 0.5,
      maxChildSize    : 0.95,
      builder: (_, scrollCtrl) => Container(
        decoration: const BoxDecoration(
          color       : Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: Column(
          children: [
            const SizedBox(height: 10),
            Container(
              width : 40, height: 4,
              decoration: BoxDecoration(
                color       : const Color(0xFFDDDDDD),
                borderRadius: BorderRadius.circular(2),
              ),
            ),
            const SizedBox(height: 16),
            const Text(
              'Edit Profil',
              style: TextStyle(
                fontSize  : 18,
                fontWeight: FontWeight.bold,
                color     : Color(0xFF1A1A2E),
              ),
            ),
            const Divider(height: 24),
            Expanded(
              child: ListView(
                controller: scrollCtrl,
                padding   : const EdgeInsets.fromLTRB(20, 0, 20, 24),
                children  : [
                  _buildField('Nama Lengkap',  _namaCtrl,     Icons.person_outline),
                  _buildField('Username',       _usernameCtrl, Icons.alternate_email),
                  _buildField('Email',          _emailCtrl,    Icons.email_outlined,
                    keyboardType: TextInputType.emailAddress),
                  _buildField('No. Handphone',  _hpCtrl,       Icons.phone_outlined,
                    keyboardType: TextInputType.phone),
                  _buildField('Alamat',         _alamatCtrl,   Icons.location_on_outlined,
                    maxLines: 3),
                  const SizedBox(height: 28),
                  SizedBox(
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primaryPurple,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                        elevation: 0,
                      ),
                      child: _isLoading
                          ? const SizedBox(
                              width : 20, height: 20,
                              child : CircularProgressIndicator(
                                color: Colors.white, strokeWidth: 2,
                              ),
                            )
                          : const Text(
                              'Simpan Perubahan',
                              style: TextStyle(
                                fontSize  : 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildField(
    String label,
    TextEditingController ctrl,
    IconData icon, {
    TextInputType keyboardType = TextInputType.text,
    int maxLines = 1,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          fieldLabel(label),
          const SizedBox(height: 8),
          TextField(
            controller  : ctrl,
            keyboardType: keyboardType,
            maxLines    : maxLines,
            decoration  : InputDecoration(
              prefixIcon: Icon(icon, size: 20, color: AppColors.primaryPurple.withOpacity(0.6)),
              filled    : true,
              fillColor : const Color(0xFFF8F8F8),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide  : const BorderSide(color: Color(0xFFE0E0E0)),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide  : const BorderSide(color: Color(0xFFE0E0E0)),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide  : const BorderSide(
                  color: AppColors.primaryPurple, width: 1.5,
                ),
              ),
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 14, vertical: 14,
              ),
            ),
          ),
        ],
      ),
    );
  }
}