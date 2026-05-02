// Plugin cung cấp $auth object toàn cục cho tất cả components
import authService from './authService';

export default {
    install(app) {
        // Tạo $auth object toàn cục
        app.config.globalProperties.$auth = {
            // Lấy thông tin user
            user: () => authService.getCurrentUser(),
            role: () => authService.getCurrentRole(),

            // Kiểm tra authentication
            isAuthenticated: () => authService.isAuthenticated(),

            // Kiểm tra quyền hệ thống được backend trả về sau đăng nhập.
            hasRole: (roles) => authService.hasRole(roles),
            hasPermission: () => authService.isAuthenticated(),
            can: () => authService.isAuthenticated(),
            canShow: () => authService.isAuthenticated(),

            // Đăng xuất
            logout: () => authService.logout(),

            // Lấy dashboard URL
            getDashboardUrl: () => authService.getDashboardUrl(),

            // Lấy layout theo role
            getLayout: () => authService.getLayoutByRole(),
        };

        // Directive v-auth kiểm tra role khi truyền role, nếu không truyền thì chỉ kiểm tra đăng nhập.
        app.directive('auth', {
            mounted(el, binding) {
                const roles = binding.value;
                const isAllowed = roles ? authService.hasRole(roles) : authService.isAuthenticated();

                if (!isAllowed) {
                    el.style.display = 'none';
                }
            }
        });

        // Directive v-can chỉ kiểm tra đã đăng nhập.
        app.directive('can', {
            mounted(el) {
                if (!authService.isAuthenticated()) {
                    el.style.display = 'none';
                }
            }
        });
    }
};
