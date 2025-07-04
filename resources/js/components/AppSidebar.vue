<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { IdCard, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { User2 } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
const page = usePage();


const roleId:number = page.props.auth.user?.role_id;
const commonNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];


const adminNavItems: NavItem[] = [
    {
        title: 'Role Management',
        href: '/role',
        icon: IdCard,
    },
    {
        title: 'User Management',
        href: '/user',
        icon: User2,
    },
];

const getNavItemsByRole = () => {
    if (roleId === 1) {
        return commonNavItems.concat(adminNavItems);
    } else {
        return commonNavItems;
    }
}

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="getNavItemsByRole()"/>
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
