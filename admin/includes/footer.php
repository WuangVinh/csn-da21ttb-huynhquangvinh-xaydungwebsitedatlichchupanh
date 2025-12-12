            </div> <!-- Đóng div.content -->
        </div> <!-- Đóng div.d-flex -->

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Custom Admin JS -->
        <script src="assets/js/admin.js"></script>
        
        <!-- DataTables & Plugins -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
        
        <script>
        // Highlight active menu item
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.sidebar .list-group-item').forEach(item => {
            if(item.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });

        // Khởi tạo DataTables
        if($.fn.DataTable) {
            $('#bookingTable').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json"
                }
            });
        }
        </script>
    </body>
</html>
