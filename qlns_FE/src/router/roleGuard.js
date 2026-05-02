import { createToaster } from "@meforma/vue-toaster";
import authService from "../services/authService";

const toaster = createToaster({ position: "top-right" });

/**
 * Role-based Route Guard
 * Kiểm tra xem user có role được phép truy cập route hay không
 */
export default function roleGuard(to, from, next) {
    // Nếu route không yêu cầu role cụ thể, cho phép qua
    if (!to.meta.requiredRoles) {
        next();
        return;
    }

    const requiredRoles = to.meta.requiredRoles;

    if (authService.hasRole(requiredRoles)) {
        next();
        return;
    }

    toaster.error("Bạn không có quyền truy cập trang này!");
    next(authService.getDashboardUrl());
}
