import { useAuth } from "@/store/auth.js";

export const hasPermission = (permission) => {
  const auth = useAuth();
  const permissions = auth.user.permissions;

  if (auth.user.owner) return true;

  return permissions.includes(permission);
};

export const directiveCan = {
  mounted(el, binding) {
    if (!hasPermission(binding.value)) {
      el.style.display = "none";
    }
  },
};
