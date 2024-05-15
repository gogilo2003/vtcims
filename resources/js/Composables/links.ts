import { iMenu } from "@/interfaces";

export const useLinks = (menuItems: iMenu[], permissions: string[]): iMenu[] => {
    return menuItems.filter(item => {
        if (item.name === '') {
            // If it's a submenu, filter its items recursively
            item.items = useLinks(item?.items, permissions);
            return item?.items.length > 0;
        } else {
            // If it's a regular menu item, check if the user has permission
            return permissions.includes(item.name);
        }
    });
}
