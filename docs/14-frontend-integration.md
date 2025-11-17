# Frontend Integration Guide / មគ្គុទេសក៍ការរួមបញ្ចូល Frontend

Complete guide to integrating this package with popular frontend frameworks.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការរួមបញ្ចូល package នេះជាមួយ frontend frameworks ដែលពេញនិយម។

## Overview / ទិដ្ឋភាពទូទៅ

This guide provides examples for integrating authentication, permissions, and CRUD operations with various frontend frameworks.
មគ្គុទេសក៍នេះផ្តល់ឧទាហរណ៍សម្រាប់ការរួមបញ្ចូល authentication, permissions, និង CRUD operations ជាមួយ frontend frameworks ផ្សេងៗ។

## Table of Contents / មាតិកា

- [Vue 3](#vue-3)
- [Nuxt 3](#nuxt-3)
- [React](#react)
- [Next.js](#nextjs)
- [TanStack Start](#tanstack-start)
- [Angular](#angular)
- [SvelteKit](#sveltekit)

---

## Vue 3 / Vue 3

### Setup / ការរៀបចំ

```bash
npm install @vue/apollo-composable graphql graphql-tag
```

### GraphQL Client Setup / ការរៀបចំ GraphQL Client

```typescript
// composables/useApollo.ts
import { createApolloClient } from '@vue/apollo-composable'
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client/core'
import { setContext } from '@apollo/client/link/context'

const httpLink = createHttpLink({
  uri: 'http://localhost:8000/graphql',
})

const authLink = setContext((_, { headers }) => {
  const token = localStorage.getItem('auth_token')
  
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : '',
    },
  }
})

export const apolloClient = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache(),
})
```

### Authentication Composable / Authentication Composable

```typescript
// composables/useAuth.ts
import { ref, computed } from 'vue'
import { useMutation, useQuery } from '@vue/apollo-composable'
import { gql } from 'graphql-tag'

const token = ref<string | null>(localStorage.getItem('auth_token'))
const user = ref<any>(null)

export const useAuth = () => {
  // Login mutation
  const { mutate: loginMutation } = useMutation(gql`
    mutation Login($email: String!, $password: String!, $deviceName: String) {
      login(email: $email, password: $password, device_name: $deviceName) {
        user {
          id
          name
          email
          roles {
            name
          }
          permissions {
            name
          }
        }
        token
        token_type
      }
    }
  `)

  // Get current user query
  const { result: meResult, refetch: refetchMe } = useQuery(gql`
    query Me {
      me {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
    }
  `, null, {
    skip: !token.value,
  })

  // Logout mutation
  const { mutate: logoutMutation } = useMutation(gql`
    mutation Logout {
      logout
    }
  `)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  const login = async (email: string, password: string, deviceName?: string) => {
    try {
      const result = await loginMutation({
        email,
        password,
        deviceName: deviceName || 'Web Browser',
      })

      if (result?.data?.login) {
        token.value = result.data.login.token
        user.value = result.data.login.user
        localStorage.setItem('auth_token', token.value)
        await refetchMe()
        return result.data.login
      }
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  }

  const logout = async () => {
    try {
      await logoutMutation()
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
    }
  }

  const hasRole = (role: string) => {
    return user.value?.roles?.some((r: any) => r.name === role) ?? false
  }

  const hasPermission = (permission: string) => {
    return user.value?.permissions?.some((p: any) => p.name === permission) ?? false
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    logout,
    hasRole,
    hasPermission,
    refetchMe,
  }
}
```

### CRUD Operations / CRUD Operations

```vue
<!-- components/PostList.vue -->
<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <div v-for="post in posts" :key="post.id" class="post-item">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <button 
          v-if="hasPermission('edit posts')" 
          @click="editPost(post.id)"
        >
          Edit
        </button>
        <button 
          v-if="hasPermission('delete posts')" 
          @click="deletePost(post.id)"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useQuery, useMutation } from '@vue/apollo-composable'
import { gql } from 'graphql-tag'
import { useAuth } from '@/composables/useAuth'

const { hasPermission } = useAuth()

// Query posts
const { result, loading, error, refetch } = useQuery(gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`)

const posts = computed(() => result.value?.posts ?? [])

// Delete mutation
const { mutate: deletePostMutation } = useMutation(gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`)

const deletePost = async (id: string) => {
  if (!hasPermission('delete posts')) {
    alert('You do not have permission to delete posts')
    return
  }

  try {
    await deletePostMutation({ id })
    await refetch()
  } catch (error) {
    console.error('Delete error:', error)
  }
}

const editPost = (id: string) => {
  // Navigate to edit page
  router.push(`/posts/${id}/edit`)
}
</script>
```

---

## Nuxt 3 / Nuxt 3

### Setup / ការរៀបចំ

```bash
npm install @nuxtjs/apollo
```

### Nuxt Config / Nuxt Config

```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  modules: ['@nuxtjs/apollo'],
  
  apollo: {
    clients: {
      default: {
        httpEndpoint: 'http://localhost:8000/graphql',
        httpLinkOptions: {
          headers: {
            authorization: () => {
              const token = useCookie('auth_token').value
              return token ? `Bearer ${token}` : ''
            },
          },
        },
      },
    },
  },
})
```

### Authentication Plugin / Authentication Plugin

```typescript
// plugins/auth.client.ts
export default defineNuxtPlugin(() => {
  const token = useCookie('auth_token')
  const user = useState('user', () => null)

  const loginMutation = gql`
    mutation Login($email: String!, $password: String!, $deviceName: String) {
      login(email: $email, password: $password, device_name: $deviceName) {
        user {
          id
          name
          email
          roles {
            name
          }
          permissions {
            name
          }
        }
        token
        token_type
      }
    }
  `

  const meQuery = gql`
    query Me {
      me {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
    }
  `

  return {
    provide: {
      auth: {
        async login(email: string, password: string, deviceName?: string) {
          const { data } = await useAsyncQuery(loginMutation, {
            email,
            password,
            deviceName: deviceName || 'Web Browser',
          })

          if (data.value?.login) {
            token.value = data.value.login.token
            user.value = data.value.login.user
            await refreshCookie('auth_token')
            return data.value.login
          }
        },

        async logout() {
          const { mutate } = useMutation(gql`
            mutation Logout {
              logout
            }
          `)

          try {
            await mutate()
          } finally {
            token.value = null
            user.value = null
            await navigateTo('/login')
          }
        },

        hasRole(role: string) {
          return user.value?.roles?.some((r: any) => r.name === role) ?? false
        },

        hasPermission(permission: string) {
          return user.value?.permissions?.some((p: any) => p.name === permission) ?? false
        },

        user: readonly(user),
        isAuthenticated: computed(() => !!token.value && !!user.value),
      },
    },
  }
})
```

### CRUD Page Example / ឧទាហរណ៍ CRUD Page

```vue
<!-- pages/posts/index.vue -->
<template>
  <div>
    <h1>Posts</h1>
    
    <NuxtLink 
      v-if="$auth.hasPermission('create posts')" 
      to="/posts/create"
    >
      Create Post
    </NuxtLink>

    <div v-if="pending">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <div v-for="post in data?.posts" :key="post.id">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <button 
          v-if="$auth.hasPermission('edit posts')" 
          @click="editPost(post.id)"
        >
          Edit
        </button>
        <button 
          v-if="$auth.hasPermission('delete posts')" 
          @click="deletePost(post.id)"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth',
})

const { $auth } = useNuxtApp()

const postsQuery = gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`

const { data, pending, error, refresh } = await useAsyncQuery(postsQuery)

const deletePostMutation = gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`

const deletePost = async (id: string) => {
  if (!$auth.hasPermission('delete posts')) {
    alert('You do not have permission to delete posts')
    return
  }

  const { mutate } = useMutation(deletePostMutation)
  await mutate({ id })
  await refresh()
}

const editPost = (id: string) => {
  navigateTo(`/posts/${id}/edit`)
}
</script>
```

---

## React / React

### Setup / ការរៀបចំ

```bash
npm install @apollo/client graphql
```

### Apollo Client Setup / ការរៀបចំ Apollo Client

```typescript
// lib/apollo.ts
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client'
import { setContext } from '@apollo/client/link/context'

const httpLink = createHttpLink({
  uri: 'http://localhost:8000/graphql',
})

const authLink = setContext((_, { headers }) => {
  const token = localStorage.getItem('auth_token')
  
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : '',
    },
  }
})

export const apolloClient = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache(),
})
```

### Auth Context / Auth Context

```typescript
// contexts/AuthContext.tsx
import React, { createContext, useContext, useState, useEffect } from 'react'
import { useMutation, useQuery, gql } from '@apollo/client'

const LOGIN_MUTATION = gql`
  mutation Login($email: String!, $password: String!, $deviceName: String) {
    login(email: $email, password: $password, device_name: $deviceName) {
      user {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
      token
      token_type
    }
  }
`

const ME_QUERY = gql`
  query Me {
    me {
      id
      name
      email
      roles {
        name
      }
      permissions {
        name
      }
    }
  }
`

const LOGOUT_MUTATION = gql`
  mutation Logout {
    logout
  }
`

interface AuthContextType {
  user: any
  token: string | null
  isAuthenticated: boolean
  login: (email: string, password: string, deviceName?: string) => Promise<void>
  logout: () => Promise<void>
  hasRole: (role: string) => boolean
  hasPermission: (permission: string) => boolean
  loading: boolean
}

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [token, setToken] = useState<string | null>(
    localStorage.getItem('auth_token')
  )

  const { data: meData, loading: meLoading, refetch: refetchMe } = useQuery(ME_QUERY, {
    skip: !token,
    errorPolicy: 'ignore',
  })

  const [loginMutation] = useMutation(LOGIN_MUTATION)
  const [logoutMutation] = useMutation(LOGOUT_MUTATION)

  const user = meData?.me || null

  const login = async (email: string, password: string, deviceName?: string) => {
    const { data } = await loginMutation({
      variables: {
        email,
        password,
        deviceName: deviceName || 'Web Browser',
      },
    })

    if (data?.login) {
      setToken(data.login.token)
      localStorage.setItem('auth_token', data.login.token)
      await refetchMe()
    }
  }

  const logout = async () => {
    try {
      await logoutMutation()
    } finally {
      setToken(null)
      localStorage.removeItem('auth_token')
    }
  }

  const hasRole = (role: string) => {
    return user?.roles?.some((r: any) => r.name === role) ?? false
  }

  const hasPermission = (permission: string) => {
    return user?.permissions?.some((p: any) => p.name === permission) ?? false
  }

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        isAuthenticated: !!token && !!user,
        login,
        logout,
        hasRole,
        hasPermission,
        loading: meLoading,
      }}
    >
      {children}
    </AuthContext.Provider>
  )
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider')
  }
  return context
}
```

### CRUD Component / CRUD Component

```tsx
// components/PostList.tsx
import React from 'react'
import { useQuery, useMutation, gql } from '@apollo/client'
import { useAuth } from '@/contexts/AuthContext'

const POSTS_QUERY = gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`

const DELETE_POST_MUTATION = gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`

export const PostList: React.FC = () => {
  const { hasPermission } = useAuth()
  const { data, loading, error, refetch } = useQuery(POSTS_QUERY)
  const [deletePost] = useMutation(DELETE_POST_MUTATION)

  const handleDelete = async (id: string) => {
    if (!hasPermission('delete posts')) {
      alert('You do not have permission to delete posts')
      return
    }

    try {
      await deletePost({ variables: { id } })
      await refetch()
    } catch (error) {
      console.error('Delete error:', error)
    }
  }

  if (loading) return <div>Loading...</div>
  if (error) return <div>Error: {error.message}</div>

  return (
    <div>
      {data?.posts?.map((post: any) => (
        <div key={post.id}>
          <h3>{post.title}</h3>
          <p>{post.content}</p>
          {hasPermission('edit posts') && (
            <button onClick={() => navigate(`/posts/${post.id}/edit`)}>
              Edit
            </button>
          )}
          {hasPermission('delete posts') && (
            <button onClick={() => handleDelete(post.id)}>Delete</button>
          )}
        </div>
      ))}
    </div>
  )
}
```

---

## Next.js / Next.js

### Setup / ការរៀបចំ

```bash
npm install @apollo/client graphql
```

### Apollo Client Setup / ការរៀបចំ Apollo Client

```typescript
// lib/apollo.ts
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client'
import { setContext } from '@apollo/client/link/context'

const httpLink = createHttpLink({
  uri: process.env.NEXT_PUBLIC_GRAPHQL_URL || 'http://localhost:8000/graphql',
})

const authLink = setContext((_, { headers }) => {
  const token = typeof window !== 'undefined' 
    ? localStorage.getItem('auth_token') 
    : null
  
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : '',
    },
  }
})

export const apolloClient = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache(),
  ssrMode: typeof window === 'undefined',
})
```

### Auth Hook / Auth Hook

```typescript
// hooks/useAuth.ts
import { useState, useEffect } from 'react'
import { useMutation, useQuery, gql } from '@apollo/client'

const LOGIN_MUTATION = gql`
  mutation Login($email: String!, $password: String!, $deviceName: String) {
    login(email: $email, password: $password, device_name: $deviceName) {
      user {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
      token
      token_type
    }
  }
`

const ME_QUERY = gql`
  query Me {
    me {
      id
      name
      email
      roles {
        name
      }
      permissions {
        name
      }
    }
  }
`

export const useAuth = () => {
  const [token, setToken] = useState<string | null>(null)

  useEffect(() => {
    if (typeof window !== 'undefined') {
      setToken(localStorage.getItem('auth_token'))
    }
  }, [])

  const { data: meData, loading, refetch } = useQuery(ME_QUERY, {
    skip: !token,
    errorPolicy: 'ignore',
  })

  const [loginMutation] = useMutation(LOGIN_MUTATION)
  const [logoutMutation] = useMutation(gql`
    mutation Logout {
      logout
    }
  `)

  const user = meData?.me || null

  const login = async (email: string, password: string, deviceName?: string) => {
    const { data } = await loginMutation({
      variables: {
        email,
        password,
        deviceName: deviceName || 'Web Browser',
      },
    })

    if (data?.login) {
      const newToken = data.login.token
      setToken(newToken)
      localStorage.setItem('auth_token', newToken)
      await refetch()
      return data.login
    }
  }

  const logout = async () => {
    try {
      await logoutMutation()
    } finally {
      setToken(null)
      localStorage.removeItem('auth_token')
    }
  }

  const hasRole = (role: string) => {
    return user?.roles?.some((r: any) => r.name === role) ?? false
  }

  const hasPermission = (permission: string) => {
    return user?.permissions?.some((p: any) => p.name === permission) ?? false
  }

  return {
    user,
    token,
    isAuthenticated: !!token && !!user,
    loading,
    login,
    logout,
    hasRole,
    hasPermission,
  }
}
```

### CRUD Page / CRUD Page

```tsx
// app/posts/page.tsx
'use client'

import { useQuery, useMutation, gql } from '@apollo/client'
import { useAuth } from '@/hooks/useAuth'
import { useRouter } from 'next/navigation'

const POSTS_QUERY = gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`

const DELETE_POST_MUTATION = gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`

export default function PostsPage() {
  const { hasPermission } = useAuth()
  const router = useRouter()
  const { data, loading, error, refetch } = useQuery(POSTS_QUERY)
  const [deletePost] = useMutation(DELETE_POST_MUTATION)

  const handleDelete = async (id: string) => {
    if (!hasPermission('delete posts')) {
      alert('You do not have permission to delete posts')
      return
    }

    try {
      await deletePost({ variables: { id } })
      await refetch()
    } catch (error) {
      console.error('Delete error:', error)
    }
  }

  if (loading) return <div>Loading...</div>
  if (error) return <div>Error: {error.message}</div>

  return (
    <div>
      <h1>Posts</h1>
      {hasPermission('create posts') && (
        <button onClick={() => router.push('/posts/create')}>
          Create Post
        </button>
      )}
      {data?.posts?.map((post: any) => (
        <div key={post.id}>
          <h3>{post.title}</h3>
          <p>{post.content}</p>
          {hasPermission('edit posts') && (
            <button onClick={() => router.push(`/posts/${post.id}/edit`)}>
              Edit
            </button>
          )}
          {hasPermission('delete posts') && (
            <button onClick={() => handleDelete(post.id)}>Delete</button>
          )}
        </div>
      ))}
    </div>
  )
}
```

---

## TanStack Start / TanStack Start

### Setup / ការរៀបចំ

```bash
npm install @tanstack/start @tanstack/react-query graphql-request
```

### GraphQL Client / GraphQL Client

```typescript
// lib/graphql.ts
import { GraphQLClient } from 'graphql-request'

export const graphqlClient = new GraphQLClient(
  'http://localhost:8000/graphql',
  {
    headers: () => {
      const token = typeof window !== 'undefined'
        ? localStorage.getItem('auth_token')
        : null

      return {
        authorization: token ? `Bearer ${token}` : '',
      }
    },
  }
)
```

### Auth Store / Auth Store

```typescript
// stores/auth.ts
import { createStore } from '@tanstack/react-store'
import { graphqlClient } from '@/lib/graphql'
import { gql } from 'graphql-request'

const LOGIN_MUTATION = gql`
  mutation Login($email: String!, $password: String!, $deviceName: String) {
    login(email: $email, password: $password, device_name: $deviceName) {
      user {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
      token
      token_type
    }
  }
`

const ME_QUERY = gql`
  query Me {
    me {
      id
      name
      email
      roles {
        name
      }
      permissions {
        name
      }
    }
  }
`

interface AuthState {
  user: any | null
  token: string | null
  loading: boolean
}

class AuthStore extends createStore<AuthState>({
  user: null,
  token: typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null,
  loading: false,
}) {
  async login(email: string, password: string, deviceName?: string) {
    this.setState((state) => ({ ...state, loading: true }))

    try {
      const data = await graphqlClient.request(LOGIN_MUTATION, {
        email,
        password,
        deviceName: deviceName || 'Web Browser',
      })

      if (data?.login) {
        const token = data.login.token
        this.setState({
          user: data.login.user,
          token,
          loading: false,
        })

        if (typeof window !== 'undefined') {
          localStorage.setItem('auth_token', token)
        }
      }
    } catch (error) {
      this.setState((state) => ({ ...state, loading: false }))
      throw error
    }
  }

  async logout() {
    try {
      await graphqlClient.request(gql`
        mutation Logout {
          logout
        }
      `)
    } finally {
      this.setState({
        user: null,
        token: null,
        loading: false,
      })

      if (typeof window !== 'undefined') {
        localStorage.removeItem('auth_token')
      }
    }
  }

  async fetchMe() {
    if (!this.state.token) return

    try {
      const data = await graphqlClient.request(ME_QUERY)
      this.setState((state) => ({
        ...state,
        user: data?.me || null,
      }))
    } catch (error) {
      console.error('Fetch me error:', error)
    }
  }

  hasRole(role: string) {
    return this.state.user?.roles?.some((r: any) => r.name === role) ?? false
  }

  hasPermission(permission: string) {
    return this.state.user?.permissions?.some((p: any) => p.name === permission) ?? false
  }

  get isAuthenticated() {
    return !!this.state.token && !!this.state.user
  }
}

export const authStore = new AuthStore()
```

### CRUD Component / CRUD Component

```tsx
// components/PostList.tsx
import { useQuery, useMutation } from '@tanstack/react-query'
import { graphqlClient } from '@/lib/graphql'
import { gql } from 'graphql-request'
import { useStore } from '@tanstack/react-store'
import { authStore } from '@/stores/auth'

const POSTS_QUERY = gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`

const DELETE_POST_MUTATION = gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`

export function PostList() {
  const auth = useStore(authStore)

  const { data, isLoading, error, refetch } = useQuery({
    queryKey: ['posts'],
    queryFn: () => graphqlClient.request(POSTS_QUERY),
  })

  const deleteMutation = useMutation({
    mutationFn: (id: string) =>
      graphqlClient.request(DELETE_POST_MUTATION, { id }),
    onSuccess: () => refetch(),
  })

  const handleDelete = (id: string) => {
    if (!auth.hasPermission('delete posts')) {
      alert('You do not have permission to delete posts')
      return
    }

    deleteMutation.mutate(id)
  }

  if (isLoading) return <div>Loading...</div>
  if (error) return <div>Error: {error.message}</div>

  return (
    <div>
      {data?.posts?.map((post: any) => (
        <div key={post.id}>
          <h3>{post.title}</h3>
          <p>{post.content}</p>
          {auth.hasPermission('edit posts') && (
            <button onClick={() => navigate(`/posts/${post.id}/edit`)}>
              Edit
            </button>
          )}
          {auth.hasPermission('delete posts') && (
            <button onClick={() => handleDelete(post.id)}>Delete</button>
          )}
        </div>
      ))}
    </div>
  )
}
```

---

## Angular / Angular

### Setup / ការរៀបចំ

```bash
ng add apollo-angular
npm install graphql graphql-tag
```

### GraphQL Module / GraphQL Module

```typescript
// app/graphql.module.ts
import { NgModule } from '@angular/core'
import { ApolloModule, APOLLO_OPTIONS } from 'apollo-angular'
import { HttpLink } from 'apollo-angular/http'
import { InMemoryCache } from '@apollo/client/core'
import { setContext } from '@apollo/client/link/context'
import { ApolloClientOptions } from '@apollo/client/core'

const uri = 'http://localhost:8000/graphql'

export function createApollo(httpLink: HttpLink): ApolloClientOptions<any> {
  const http = httpLink.create({ uri })

  const authLink = setContext((_, { headers }) => {
    const token = localStorage.getItem('auth_token')
    
    return {
      headers: {
        ...headers,
        authorization: token ? `Bearer ${token}` : '',
      },
    }
  })

  return {
    link: authLink.concat(http),
    cache: new InMemoryCache(),
  }
}

@NgModule({
  exports: [ApolloModule],
  providers: [
    {
      provide: APOLLO_OPTIONS,
      useFactory: createApollo,
      deps: [HttpLink],
    },
  ],
})
export class GraphQLModule {}
```

### Auth Service / Auth Service

```typescript
// app/services/auth.service.ts
import { Injectable } from '@angular/core'
import { BehaviorSubject, Observable } from 'rxjs'
import { Apollo, gql } from 'apollo-angular'

const LOGIN_MUTATION = gql`
  mutation Login($email: String!, $password: String!, $deviceName: String) {
    login(email: $email, password: $password, device_name: $deviceName) {
      user {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
      token
      token_type
    }
  }
`

const ME_QUERY = gql`
  query Me {
    me {
      id
      name
      email
      roles {
        name
      }
      permissions {
        name
      }
    }
  }
`

const LOGOUT_MUTATION = gql`
  mutation Logout {
    logout
  }
`

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private userSubject = new BehaviorSubject<any>(null)
  public user$ = this.userSubject.asObservable()

  private tokenSubject = new BehaviorSubject<string | null>(
    localStorage.getItem('auth_token')
  )
  public token$ = this.tokenSubject.asObservable()

  constructor(private apollo: Apollo) {
    this.loadUser()
  }

  get user(): any {
    return this.userSubject.value
  }

  get token(): string | null {
    return this.tokenSubject.value
  }

  get isAuthenticated(): boolean {
    return !!this.token && !!this.user
  }

  async login(email: string, password: string, deviceName?: string): Promise<void> {
    const result = await this.apollo
      .mutate({
        mutation: LOGIN_MUTATION,
        variables: {
          email,
          password,
          deviceName: deviceName || 'Web Browser',
        },
      })
      .toPromise()

    if (result?.data?.login) {
      const token = result.data.login.token
      this.tokenSubject.next(token)
      this.userSubject.next(result.data.login.user)
      localStorage.setItem('auth_token', token)
      await this.loadUser()
    }
  }

  async logout(): Promise<void> {
    try {
      await this.apollo.mutate({ mutation: LOGOUT_MUTATION }).toPromise()
    } finally {
      this.tokenSubject.next(null)
      this.userSubject.next(null)
      localStorage.removeItem('auth_token')
    }
  }

  async loadUser(): Promise<void> {
    if (!this.token) return

    try {
      const result = await this.apollo
        .query({ query: ME_QUERY })
        .toPromise()

      if (result?.data?.me) {
        this.userSubject.next(result.data.me)
      }
    } catch (error) {
      console.error('Load user error:', error)
    }
  }

  hasRole(role: string): boolean {
    return this.user?.roles?.some((r: any) => r.name === role) ?? false
  }

  hasPermission(permission: string): boolean {
    return this.user?.permissions?.some((p: any) => p.name === permission) ?? false
  }
}
```

### CRUD Component / CRUD Component

```typescript
// app/components/post-list/post-list.component.ts
import { Component, OnInit } from '@angular/core'
import { Apollo, gql } from 'apollo-angular'
import { AuthService } from '@/services/auth.service'
import { Router } from '@angular/router'

const POSTS_QUERY = gql`
  query Posts {
    posts {
      id
      title
      content
      user {
        id
        name
      }
    }
  }
`

const DELETE_POST_MUTATION = gql`
  mutation DeletePost($id: ID!) {
    deletePost(id: $id) {
      id
    }
  }
`

@Component({
  selector: 'app-post-list',
  template: `
    <div>
      <h1>Posts</h1>
      <button 
        *ngIf="authService.hasPermission('create posts')" 
        (click)="createPost()"
      >
        Create Post
      </button>
      <div *ngIf="loading">Loading...</div>
      <div *ngIf="error">Error: {{ error.message }}</div>
      <div *ngFor="let post of posts">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <button 
          *ngIf="authService.hasPermission('edit posts')" 
          (click)="editPost(post.id)"
        >
          Edit
        </button>
        <button 
          *ngIf="authService.hasPermission('delete posts')" 
          (click)="deletePost(post.id)"
        >
          Delete
        </button>
      </div>
    </div>
  `,
})
export class PostListComponent implements OnInit {
  posts: any[] = []
  loading = false
  error: any = null

  constructor(
    private apollo: Apollo,
    public authService: AuthService,
    private router: Router
  ) {}

  ngOnInit() {
    this.loadPosts()
  }

  loadPosts() {
    this.loading = true
    this.apollo
      .query({ query: POSTS_QUERY })
      .subscribe({
        next: (result: any) => {
          this.posts = result.data?.posts || []
          this.loading = false
        },
        error: (error) => {
          this.error = error
          this.loading = false
        },
      })
  }

  deletePost(id: string) {
    if (!this.authService.hasPermission('delete posts')) {
      alert('You do not have permission to delete posts')
      return
    }

    this.apollo
      .mutate({
        mutation: DELETE_POST_MUTATION,
        variables: { id },
      })
      .subscribe({
        next: () => {
          this.loadPosts()
        },
        error: (error) => {
          console.error('Delete error:', error)
        },
      })
  }

  editPost(id: string) {
    this.router.navigate([`/posts/${id}/edit`])
  }

  createPost() {
    this.router.navigate(['/posts/create'])
  }
}
```

---

## SvelteKit / SvelteKit

### Setup / ការរៀបចំ

```bash
npm install @apollo/client graphql
```

### Apollo Client Setup / ការរៀបចំ Apollo Client

```typescript
// lib/apollo.ts
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client'
import { setContext } from '@apollo/client/link/context'
import { browser } from '$app/environment'

const httpLink = createHttpLink({
  uri: 'http://localhost:8000/graphql',
})

const authLink = setContext((_, { headers }) => {
  const token = browser ? localStorage.getItem('auth_token') : null
  
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : '',
    },
  }
})

export const apolloClient = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache(),
  ssrMode: !browser,
})
```

### Auth Store / Auth Store

```typescript
// stores/auth.ts
import { writable } from 'svelte/store'
import { browser } from '$app/environment'
import { apolloClient } from '@/lib/apollo'
import { gql } from '@apollo/client'

const LOGIN_MUTATION = gql`
  mutation Login($email: String!, $password: String!, $deviceName: String) {
    login(email: $email, password: $password, device_name: $deviceName) {
      user {
        id
        name
        email
        roles {
          name
        }
        permissions {
          name
        }
      }
      token
      token_type
    }
  }
`

const ME_QUERY = gql`
  query Me {
    me {
      id
      name
      email
      roles {
        name
      }
      permissions {
        name
      }
    }
  }
`

export const user = writable<any>(null)
export const token = writable<string | null>(
  browser ? localStorage.getItem('auth_token') : null
)

export const authStore = {
  subscribe: user.subscribe,
  
  async login(email: string, password: string, deviceName?: string) {
    const { data } = await apolloClient.mutate({
      mutation: LOGIN_MUTATION,
      variables: {
        email,
        password,
        deviceName: deviceName || 'Web Browser',
      },
    })

    if (data?.login) {
      const newToken = data.login.token
      token.set(newToken)
      user.set(data.login.user)
      
      if (browser) {
        localStorage.setItem('auth_token', newToken)
      }
      
      await this.loadUser()
      return data.login
    }
  },

  async logout() {
    try {
      await apolloClient.mutate({
        mutation: gql`
          mutation Logout {
            logout
          }
        `,
      })
    } finally {
      token.set(null)
      user.set(null)
      
      if (browser) {
        localStorage.removeItem('auth_token')
      }
    }
  },

  async loadUser() {
    const currentToken = browser ? localStorage.getItem('auth_token') : null
    if (!currentToken) return

    try {
      const { data } = await apolloClient.query({ query: ME_QUERY })
      if (data?.me) {
        user.set(data.me)
      }
    } catch (error) {
      console.error('Load user error:', error)
    }
  },

  hasRole(role: string): boolean {
    let currentUser: any
    user.subscribe((u) => (currentUser = u))()
    return currentUser?.roles?.some((r: any) => r.name === role) ?? false
  },

  hasPermission(permission: string): boolean {
    let currentUser: any
    user.subscribe((u) => (currentUser = u))()
    return currentUser?.permissions?.some((p: any) => p.name === permission) ?? false
  },
}
```

### CRUD Component / CRUD Component

```svelte
<!-- components/PostList.svelte -->
<script lang="ts">
  import { onMount } from 'svelte'
  import { apolloClient } from '@/lib/apollo'
  import { authStore } from '@/stores/auth'
  import { gql } from '@apollo/client'

  const POSTS_QUERY = gql`
    query Posts {
      posts {
        id
        title
        content
        user {
          id
          name
        }
      }
    }
  `

  const DELETE_POST_MUTATION = gql`
    mutation DeletePost($id: ID!) {
      deletePost(id: $id) {
        id
      }
    }
  `

  let posts: any[] = []
  let loading = false
  let error: any = null

  onMount(() => {
    loadPosts()
  })

  async function loadPosts() {
    loading = true
    try {
      const { data } = await apolloClient.query({ query: POSTS_QUERY })
      posts = data?.posts || []
    } catch (e) {
      error = e
    } finally {
      loading = false
    }
  }

  async function deletePost(id: string) {
    if (!authStore.hasPermission('delete posts')) {
      alert('You do not have permission to delete posts')
      return
    }

    try {
      await apolloClient.mutate({
        mutation: DELETE_POST_MUTATION,
        variables: { id },
      })
      await loadPosts()
    } catch (e) {
      console.error('Delete error:', e)
    }
  }
</script>

<div>
  <h1>Posts</h1>
  {#if loading}
    <div>Loading...</div>
  {:else if error}
    <div>Error: {error.message}</div>
  {:else}
    {#each posts as post}
      <div>
        <h3>{post.title}</h3>
        <p>{post.content}</p>
        {#if authStore.hasPermission('edit posts')}
          <button on:click={() => navigate(`/posts/${post.id}/edit`)}>
            Edit
          </button>
        {/if}
        {#if authStore.hasPermission('delete posts')}
          <button on:click={() => deletePost(post.id)}>Delete</button>
        {/if}
      </div>
    {/each}
  {/if}
</div>
```

---

## Common Patterns / Patterns ទូទៅ

### Protected Routes / Routes ដែលការពារ

```typescript
// Example for React Router
const ProtectedRoute = ({ children, requiredPermission }: any) => {
  const { isAuthenticated, hasPermission } = useAuth()

  if (!isAuthenticated) {
    return <Navigate to="/login" />
  }

  if (requiredPermission && !hasPermission(requiredPermission)) {
    return <Navigate to="/unauthorized" />
  }

  return children
}
```

### Permission Directive / Permission Directive

```vue
<!-- Vue directive example -->
<script setup lang="ts">
import { useAuth } from '@/composables/useAuth'

const { hasPermission } = useAuth()

const vPermission = {
  mounted(el: HTMLElement, binding: any) {
    if (!hasPermission(binding.value)) {
      el.style.display = 'none'
    }
  },
}
</script>
```

---

## Next Steps / ជំហានបន្ត

- Read [Authentication Guide](./03-authentication.md) / អាន [មគ្គុទេសក៍ Authentication](./03-authentication.md)
- Read [Permissions & Roles Guide](./04-permissions-roles.md) / អាន [មគ្គុទេសក៍ Permissions & Roles](./04-permissions-roles.md)
- Read [Examples](./11-examples.md) / អាន [ឧទាហរណ៍](./11-examples.md)

