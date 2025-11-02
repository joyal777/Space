<script setup lang="ts">
import RegisteredUserController from "@/actions/App/Http/Controllers/Auth/RegisteredUserController";
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthBase from "@/layouts/AuthLayout.vue";
import { login } from "@/routes";
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";
import { ref } from "vue";

// Add reactive state for company registration
const hasCompany = ref(false);
</script>

<template>
  <div class="min-h-screen flex">
    <!-- Background Image Section -->
    <div class="hidden lg:flex lg:flex-1 relative">
      <img
        :src="`/images/gsettings/banner-main.png`"
        alt="Register Background"
        class="absolute inset-0 w-full h-full object-cover"
      />
    </div>

    <!-- Register Form Section -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:max-w-md">
        <AuthBase
          title="Create an account"
          description="Enter your details below to create your account"
        >
          <Head title="Register" />

          <!-- Mobile Logo (hidden on desktop) -->
          <div class="lg:hidden flex justify-center mb-6">
            <div class="flex items-center space-x-3">
              <Rocket class="h-8 w-8 text-blue-600" />
              <span class="text-xl font-bold text-gray-900">Space PM</span>
            </div>
          </div>

          <Form
            v-bind="RegisteredUserController.store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
          >
            <div class="grid gap-6">
              <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                  id="name"
                  type="text"
                  required
                  autofocus
                  :tabindex="1"
                  autocomplete="name"
                  name="name"
                  placeholder="Full name"
                />
                <InputError :message="errors.name" />
              </div>

              <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                  id="email"
                  type="email"
                  required
                  :tabindex="2"
                  autocomplete="email"
                  name="email"
                  placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
              </div>

              <!-- Company Registration Toggle -->
              <div class="grid gap-2">
                <div class="flex items-center space-x-2">
                  <input
                    id="has_company"
                    type="checkbox"
                    v-model="hasCompany"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <Label for="has_company" class="text-sm font-medium">
                    Register with a company
                  </Label>
                </div>
                <p class="text-sm text-muted-foreground">
                  You can join or create a company later if you skip this
                </p>
              </div>

              <!-- Company Fields (Conditional) -->
              <div v-if="hasCompany" class="grid gap-4 p-4 border rounded-lg bg-gray-50">
                <div class="grid gap-2">
                  <Label for="company_name" class="text-sm font-medium">
                    Company Name
                  </Label>
                  <Input
                    id="company_name"
                    type="text"
                    :tabindex="3"
                    autocomplete="organization"
                    name="company_name"
                    placeholder="Your company name"
                  />
                  <InputError :message="errors.company_name" />
                </div>

                <div class="grid gap-2">
                  <Label for="company_email" class="text-sm font-medium">
                    Company Email (Optional)
                  </Label>
                  <Input
                    id="company_email"
                    type="email"
                    :tabindex="4"
                    autocomplete="organization-email"
                    name="company_email"
                    placeholder="company@example.com"
                  />
                  <InputError :message="errors.company_email" />
                </div>

                <div class="grid gap-2">
                  <Label for="company_phone" class="text-sm font-medium">
                    Company Phone (Optional)
                  </Label>
                  <Input
                    id="company_phone"
                    type="tel"
                    :tabindex="5"
                    autocomplete="tel"
                    name="company_phone"
                    placeholder="+1 (555) 123-4567"
                  />
                  <InputError :message="errors.company_phone" />
                </div>
              </div>

              <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input
                  id="password"
                  type="password"
                  required
                  :tabindex="hasCompany ? 6 : 3"
                  autocomplete="new-password"
                  name="password"
                  placeholder="Password"
                />
                <InputError :message="errors.password" />
              </div>

              <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <Input
                  id="password_confirmation"
                  type="password"
                  required
                  :tabindex="hasCompany ? 7 : 4"
                  autocomplete="new-password"
                  name="password_confirmation"
                  placeholder="Confirm password"
                />
                <InputError :message="errors.password_confirmation" />
              </div>

              <Button
                type="submit"
                class="mt-2 w-full"
                :tabindex="hasCompany ? 8 : 5"
                :disabled="processing"
                data-test="register-user-button"
              >
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                {{ hasCompany ? "Create Account & Company" : "Create Account" }}
              </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
              Already have an account?
              <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="hasCompany ? 9 : 6"
                >Log in</TextLink
              >
            </div>
          </Form>
        </AuthBase>
      </div>
    </div>
  </div>
</template>
