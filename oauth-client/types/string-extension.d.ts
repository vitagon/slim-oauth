declare global {
  interface StringConstructor {
    format(format: string, ...args: any): string
  }

  interface String {
    capitalize(): string
  }
}

export {}