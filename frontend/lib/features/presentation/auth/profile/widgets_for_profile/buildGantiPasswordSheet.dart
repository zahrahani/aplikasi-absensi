import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/presentation/auth/profile/profileController.dart';
import 'package:presensi/features/widgets/InputField.dart';
import 'package:presensi/features/widgets/showAlert.dart';

class BuildGantiPasswordSheet extends StatefulWidget {
  final WidgetRef    ref;
  final VoidCallback onUpdated;

  const BuildGantiPasswordSheet({
    super.key,
    required this.ref,
    required this.onUpdated,
  });

  @override
  State<BuildGantiPasswordSheet> createState() =>
      _BuildGantiPasswordSheetState();
}

class _BuildGantiPasswordSheetState extends State<BuildGantiPasswordSheet> {
  final TextEditingController _lamaCtrl     = TextEditingController();
  final TextEditingController _baruCtrl     = TextEditingController();
  final TextEditingController _konfirmCtrl  = TextEditingController();

  bool _isLoading       = false;
  bool _showLama        = false;
  bool _showBaru        = false;
  bool _showKonfirm     = false;

  @override
  void dispose() {
    _lamaCtrl.dispose();
    _baruCtrl.dispose();
    _konfirmCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (_baruCtrl.text != _konfirmCtrl.text) {
      showAlert(
        context    : context,
        message    : 'Konfirmasi password tidak cocok',
        alertStatus: 'danger',
      );
      return;
    }

    setState(() => _isLoading = true);

    final result = await ProfileController.gantiPassword(
      ref                 : widget.ref,
      passwordLama        : _lamaCtrl.text,
      passwordBaru        : _baruCtrl.text,
      konfirmasiPassword  : _konfirmCtrl.text,
    );

    if (!mounted) return;
    setState(() => _isLoading = false);
    
    Navigator.pop(context);

    showAlert(
      context    : context,
      message    : result['messages'],
      alertStatus: result['success'] ? 'success' : 'danger',
    );

  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.6,
      minChildSize    : 0.4,
      maxChildSize    : 0.85,
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
              'Ganti Password',
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
                  _buildPasswordField(
                    label     : 'Password Lama',
                    ctrl      : _lamaCtrl,
                    showPass  : _showLama,
                    onToggle  : () => setState(() => _showLama = !_showLama),
                  ),
                  const SizedBox(height: 16),
                  _buildPasswordField(
                    label     : 'Password Baru',
                    ctrl      : _baruCtrl,
                    showPass  : _showBaru,
                    onToggle  : () => setState(() => _showBaru = !_showBaru),
                  ),
                  const SizedBox(height: 16),
                  _buildPasswordField(
                    label     : 'Konfirmasi Password Baru',
                    ctrl      : _konfirmCtrl,
                    showPass  : _showKonfirm,
                    onToggle  : () => setState(() => _showKonfirm = !_showKonfirm),
                  ),
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
                              'Simpan Password',
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

  Widget _buildPasswordField({
    required String                label,
    required TextEditingController ctrl,
    required bool                  showPass,
    required VoidCallback          onToggle,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        fieldLabel(label),
        const SizedBox(height: 8),
        TextField(
          controller    : ctrl,
          obscureText   : !showPass,
          decoration    : InputDecoration(
            prefixIcon: Icon(
              Icons.lock_outline,
              size : 20,
              color: AppColors.primaryPurple.withOpacity(0.6),
            ),
            suffixIcon: IconButton(
              icon : Icon(
                showPass ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                size : 20,
                color: Colors.grey,
              ),
              onPressed: onToggle,
            ),
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
    );
  }
}