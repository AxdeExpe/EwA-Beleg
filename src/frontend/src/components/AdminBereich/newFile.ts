import { useRoute } from 'vue-router';
import { __VLS_WithTemplateSlots } from './AdminBereichBox.vue';

export default await (async () => {
const { defineProps, defineSlots, defineEmits, defineExpose, defineModel, defineOptions, withDefaults, } = await import('vue');
let route = useRoute();

let isLinkActive = (routePath: string) => {
return route.path === routePath;
};

const __VLS_componentsOption = {};

const __VLS_name = 'AdminBereichBox' as const;
function __VLS_template() {
let __VLS_ctx!: InstanceType<__VLS_PickNotAny<typeof __VLS_internalComponent, new () => {}>> & {};
/* Components */
let __VLS_otherComponents!: NonNullable<typeof __VLS_internalComponent extends { components: infer C; } ? C : {}> & typeof __VLS_componentsOption;
let __VLS_own!: __VLS_SelfComponent<typeof __VLS_name, typeof __VLS_internalComponent & (new () => { $slots: typeof __VLS_slots; })>;
let __VLS_localComponents!: typeof __VLS_otherComponents & Omit<typeof __VLS_own, keyof typeof __VLS_otherComponents>;
let __VLS_components!: typeof __VLS_localComponents & __VLS_GlobalComponents & typeof __VLS_ctx;
/* Style Scoped */
type __VLS_StyleScopedClasses = {} &
{ 'active'?: boolean; } &
{ 'box'?: boolean; } &
{ 'admin-meu'?: boolean; } &
{ 'daten-bearbeitung'?: boolean; } &
{ 'button-a'?: boolean; } &
{ 'button-b'?: boolean; } &
{ 'button-c'?: boolean; } &
{ 'button-d'?: boolean; } &
{ 'e'?: boolean; };
let __VLS_styleScopedClasses!: __VLS_StyleScopedClasses | keyof __VLS_StyleScopedClasses | (keyof __VLS_StyleScopedClasses)[];
/* CSS variable injection */
/* CSS variable injection end */
let __VLS_resolvedLocalAndGlobalComponents!: {} &
__VLS_WithComponent<'RouterLink', typeof __VLS_localComponents, "RouterLink", "RouterLink", "RouterLink">;
__VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div; __VLS_intrinsicElements.div;
__VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink; __VLS_components.RouterLink;
// @ts-ignore
[RouterLink, RouterLink, RouterLink, RouterLink, RouterLink, RouterLink, RouterLink, RouterLink,];
{
const __VLS_0 = __VLS_intrinsicElements["div"];
const __VLS_1 = __VLS_elementAsFunctionalComponent(__VLS_0);
const __VLS_2 = __VLS_1({ ...{}, class: ("box"), }, ...__VLS_functionalComponentArgsRest(__VLS_1));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_0, typeof __VLS_2> & Record<string, unknown>) => void)({ ...{}, class: ("box"), });
const __VLS_3 = __VLS_pickFunctionalComponentCtx(__VLS_0, __VLS_2)!;
let __VLS_4!: __VLS_NormalizeEmits<typeof __VLS_3.emit>;
{
const __VLS_5 = __VLS_intrinsicElements["div"];
const __VLS_6 = __VLS_elementAsFunctionalComponent(__VLS_5);
const __VLS_7 = __VLS_6({ ...{}, class: ("admin-meu"), }, ...__VLS_functionalComponentArgsRest(__VLS_6));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_5, typeof __VLS_7> & Record<string, unknown>) => void)({ ...{}, class: ("admin-meu"), });
const __VLS_8 = __VLS_pickFunctionalComponentCtx(__VLS_5, __VLS_7)!;
let __VLS_9!: __VLS_NormalizeEmits<typeof __VLS_8.emit>;
{
const __VLS_10 = ({} as 'RouterLink' extends keyof typeof __VLS_ctx ? { 'RouterLink': typeof __VLS_ctx.RouterLink; } : typeof __VLS_resolvedLocalAndGlobalComponents).RouterLink;
const __VLS_11 = __VLS_asFunctionalComponent(__VLS_10, new __VLS_10({ ...{}, to: ("/admin/Benutzerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Benutzerverwaltung') })), }));
({} as { RouterLink: typeof __VLS_10; }).RouterLink;
({} as { RouterLink: typeof __VLS_10; }).RouterLink;
const __VLS_12 = __VLS_11({ ...{}, to: ("/admin/Benutzerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Benutzerverwaltung') })), }, ...__VLS_functionalComponentArgsRest(__VLS_11));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_10, typeof __VLS_12> & Record<string, unknown>) => void)({ ...{}, to: ("/admin/Benutzerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Benutzerverwaltung') })), });
const __VLS_13 = __VLS_pickFunctionalComponentCtx(__VLS_10, __VLS_12)!;
let __VLS_14!: __VLS_NormalizeEmits<typeof __VLS_13.emit>;
__VLS_styleScopedClasses = ({ 'active': isLinkActive('/admin/Benutzerverwaltung') });
(__VLS_13.slots!).default;
}
{
const __VLS_15 = ({} as 'RouterLink' extends keyof typeof __VLS_ctx ? { 'RouterLink': typeof __VLS_ctx.RouterLink; } : typeof __VLS_resolvedLocalAndGlobalComponents).RouterLink;
const __VLS_16 = __VLS_asFunctionalComponent(__VLS_15, new __VLS_15({ ...{}, to: ("/admin/Buecherverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Buecherverwaltung') })), }));
({} as { RouterLink: typeof __VLS_15; }).RouterLink;
({} as { RouterLink: typeof __VLS_15; }).RouterLink;
const __VLS_17 = __VLS_16({ ...{}, to: ("/admin/Buecherverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Buecherverwaltung') })), }, ...__VLS_functionalComponentArgsRest(__VLS_16));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_15, typeof __VLS_17> & Record<string, unknown>) => void)({ ...{}, to: ("/admin/Buecherverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Buecherverwaltung') })), });
const __VLS_18 = __VLS_pickFunctionalComponentCtx(__VLS_15, __VLS_17)!;
let __VLS_19!: __VLS_NormalizeEmits<typeof __VLS_18.emit>;
__VLS_styleScopedClasses = ({ 'active': isLinkActive('/admin/Buecherverwaltung') });
(__VLS_18.slots!).default;
}
{
const __VLS_20 = ({} as 'RouterLink' extends keyof typeof __VLS_ctx ? { 'RouterLink': typeof __VLS_ctx.RouterLink; } : typeof __VLS_resolvedLocalAndGlobalComponents).RouterLink;
const __VLS_21 = __VLS_asFunctionalComponent(__VLS_20, new __VLS_20({ ...{}, to: ("/admin/Lagerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Lagerverwaltung') })), }));
({} as { RouterLink: typeof __VLS_20; }).RouterLink;
({} as { RouterLink: typeof __VLS_20; }).RouterLink;
const __VLS_22 = __VLS_21({ ...{}, to: ("/admin/Lagerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Lagerverwaltung') })), }, ...__VLS_functionalComponentArgsRest(__VLS_21));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_20, typeof __VLS_22> & Record<string, unknown>) => void)({ ...{}, to: ("/admin/Lagerverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Lagerverwaltung') })), });
const __VLS_23 = __VLS_pickFunctionalComponentCtx(__VLS_20, __VLS_22)!;
let __VLS_24!: __VLS_NormalizeEmits<typeof __VLS_23.emit>;
__VLS_styleScopedClasses = ({ 'active': isLinkActive('/admin/Lagerverwaltung') });
(__VLS_23.slots!).default;
}
{
const __VLS_25 = ({} as 'RouterLink' extends keyof typeof __VLS_ctx ? { 'RouterLink': typeof __VLS_ctx.RouterLink; } : typeof __VLS_resolvedLocalAndGlobalComponents).RouterLink;
const __VLS_26 = __VLS_asFunctionalComponent(__VLS_25, new __VLS_25({ ...{}, to: ("/admin/Bestellungsverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Bestellungsverwaltung') })), }));
({} as { RouterLink: typeof __VLS_25; }).RouterLink;
({} as { RouterLink: typeof __VLS_25; }).RouterLink;
const __VLS_27 = __VLS_26({ ...{}, to: ("/admin/Bestellungsverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Bestellungsverwaltung') })), }, ...__VLS_functionalComponentArgsRest(__VLS_26));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_25, typeof __VLS_27> & Record<string, unknown>) => void)({ ...{}, to: ("/admin/Bestellungsverwaltung"), class: (({ 'active': __VLS_ctx.isLinkActive('/admin/Bestellungsverwaltung') })), });
const __VLS_28 = __VLS_pickFunctionalComponentCtx(__VLS_25, __VLS_27)!;
let __VLS_29!: __VLS_NormalizeEmits<typeof __VLS_28.emit>;
__VLS_styleScopedClasses = ({ 'active': isLinkActive('/admin/Bestellungsverwaltung') });
(__VLS_28.slots!).default;
}
(__VLS_8.slots!).default;
}
{
const __VLS_30 = __VLS_intrinsicElements["div"];
const __VLS_31 = __VLS_elementAsFunctionalComponent(__VLS_30);
const __VLS_32 = __VLS_31({ ...{}, class: ("daten-bearbeitung"), }, ...__VLS_functionalComponentArgsRest(__VLS_31));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_30, typeof __VLS_32> & Record<string, unknown>) => void)({ ...{}, class: ("daten-bearbeitung"), });
const __VLS_33 = __VLS_pickFunctionalComponentCtx(__VLS_30, __VLS_32)!;
let __VLS_34!: __VLS_NormalizeEmits<typeof __VLS_33.emit>;
{
const __VLS_35 = __VLS_intrinsicElements["div"];
const __VLS_36 = __VLS_elementAsFunctionalComponent(__VLS_35);
const __VLS_37 = __VLS_36({ ...{}, class: ("e"), }, ...__VLS_functionalComponentArgsRest(__VLS_36));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_35, typeof __VLS_37> & Record<string, unknown>) => void)({ ...{}, class: ("e"), });
const __VLS_38 = __VLS_pickFunctionalComponentCtx(__VLS_35, __VLS_37)!;
let __VLS_39!: __VLS_NormalizeEmits<typeof __VLS_38.emit>;
(__VLS_38.slots!).default;
}
(__VLS_33.slots!).default;
}
{
const __VLS_40 = ({} as 'Slot' extends keyof typeof __VLS_ctx ? { 'slot': typeof __VLS_ctx.Slot; } : 'slot' extends keyof typeof __VLS_ctx ? { 'slot': typeof __VLS_ctx.slot; } : typeof __VLS_resolvedLocalAndGlobalComponents).slot;
const __VLS_41 = __VLS_asFunctionalComponent(__VLS_40, new __VLS_40({ ...{}, }));
const __VLS_42 = __VLS_41({ ...{}, }, ...__VLS_functionalComponentArgsRest(__VLS_41));
({} as (props: __VLS_FunctionalComponentProps<typeof __VLS_40, typeof __VLS_42> & Record<string, unknown>) => void)({ ...{}, });
var __VLS_43 = {};
}
(__VLS_3.slots!).default;
}
if (typeof __VLS_styleScopedClasses === 'object' && !Array.isArray(__VLS_styleScopedClasses)) {
__VLS_styleScopedClasses["box"];
__VLS_styleScopedClasses["admin-meu"];
__VLS_styleScopedClasses["button-a"];
__VLS_styleScopedClasses["button-b"];
__VLS_styleScopedClasses["button-c"];
__VLS_styleScopedClasses["button-d"];
__VLS_styleScopedClasses["daten-bearbeitung"];
__VLS_styleScopedClasses["e"];
}
var __VLS_slots!: {
content?(_: typeof __VLS_43): any;
};
// @ts-ignore
[isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive, isLinkActive,];
return __VLS_slots;
}
const __VLS_internalComponent = (await import('vue')).defineComponent({
setup() {
return {
isLinkActive: isLinkActive as typeof isLinkActive,
};
},

name: 'AdminBereichBox',
});
const __VLS_component = (await import('vue')).defineComponent({
setup() {
return {};
},

name: 'AdminBereichBox',
});
return {} as __VLS_WithTemplateSlots<typeof __VLS_component, ReturnType<typeof __VLS_template>>;
})();
