# MG-DL-UX-001 — Charte UX/UI + Contenu + Twig — DahiraLink

## Version
**V1**

## Statut
**Référence de travail active**

## Objectif
Définir les standards UX/UI, contenu et architecture Twig à respecter sur l’ensemble des pages DahiraLink afin de garantir :
- une expérience mobile claire
- une navigation toujours active
- un ton cohérent avec l’identité du projet
- une base front réutilisable et maintenable
- une meilleure continuité entre développeurs

---

# 1. Positionnement produit

## 1.1 Nature du produit
DahiraLink est une plateforme web mobile-first destinée à faciliter l’organisation et la participation à des actions communautaires spirituelles (ex. khatms, initiatives collectives, partages).

## 1.2 Intention produit
Le produit doit être perçu comme :
- utile
- simple
- humble
- accessible
- communautaire
- au service d’une intention bénéfique

## 1.3 Ce que DahiraLink ne doit pas devenir
Le produit ne doit pas ressembler à :
- un SaaS froid
- une vitrine corporate
- une app startup agressive
- un site religieux rigide ou trop institutionnel


## 1.4 Positionnement d’usage réel
DahiraLink ne vise pas à remplacer les usages communautaires déjà installés (notamment WhatsApp), mais à les structurer.

Le produit doit être pensé comme un complément naturel aux échanges déjà existants, en particulier pour :
- organiser plus clairement une lecture collective
- répartir les participations plus facilement
- suivre l’avancement de manière visible
- partager une session par lien sans changer les habitudes des utilisateurs

### Conséquence UX / contenu
Le site doit régulièrement rappeler que :
- DahiraLink reste simple à partager
- DahiraLink accompagne les usages existants
- DahiraLink apporte de la clarté là où WhatsApp seul devient vite limité
---

# 2. Principes UX fondamentaux

## 2.1 Mobile-first obligatoire
Toutes les pages doivent être pensées d’abord pour smartphone.

### Règles
- lecture verticale naturelle
- sections courtes
- boutons larges
- faible charge cognitive
- scroll fluide
- compréhension immédiate

### Validation
Toute section doit être jugée d’abord en affichage mobile (~360px à 430px de large).

---

## 2.2 Navigation toujours en avant
Aucune page ne doit “s’arrêter”.

Chaque page doit toujours proposer :
- une action
- une suite logique
- une redirection utile
- une continuité de navigation

### Interdits
- pages sans CTA
- pages purement informatives sans sortie utile
- fins de page mortes
- sections sans suite logique

---

## 2.3 Lecture simple, sans mur de texte
Le contenu doit rester digeste et lisible.

### Règles
- 1 idée principale par bloc
- paragraphes courts
- titres explicites
- respiration visuelle régulière
- découpage des contenus longs

### Recommandations
- 2 à 4 phrases max par paragraphe
- 3 paragraphes max avant rupture visuelle
- si un bloc devient dense → le découper

---

## 2.4 L’utilisateur doit se sentir inclus
Le site ne doit pas donner l’impression d’être consulté “de l’extérieur”.

L’utilisateur doit sentir qu’il :
- peut participer
- fait partie d’une dynamique
- peut contribuer facilement
- est concerné par le projet

### Vocabulaire recommandé
- communauté
- ensemble
- participer
- contribuer
- partager
- votre entourage
- vos initiatives
- chacun

---

## 2.5 Le produit doit rassurer sans sur-promettre
La confiance doit venir de :
- la clarté
- la simplicité
- l’utilité
- l’humilité
- la cohérence

### Interdits de ton
- révolutionnaire
- ultime
- premium
- incroyable
- solution parfaite
- incontournable

---

# 3. Charte de contenu / ton rédactionnel

## 3.1 Ton officiel DahiraLink
Le ton doit être :
- humble
- simple
- apaisé
- sincère
- clair
- accessible
- spirituel sans lourdeur

---

## 3.2 Style rédactionnel attendu
Le contenu doit :
- aller à l’essentiel
- rester humain
- éviter le jargon inutile
- ne jamais être pompeux

### Préférer
- faciliter
- permettre
- participer
- organiser
- partager
- contribuer
- au service de
- avec simplicité

### Éviter
- disruptif
- innovation de rupture
- plateforme incontournable
- expérience immersive
- écosystème complet

---

## 3.3 Règle de contenu
Chaque bloc doit répondre à au moins une de ces questions :
- Pourquoi cette page existe ?
- Que peut faire l’utilisateur ici ?
- En quoi cela lui est utile ?
- Quelle est l’intention du projet ?
- Quelle est l’action suivante ?

Si un bloc ne répond à aucune de ces questions, il est probablement inutile.

---

# 4. Règles CTA (Call To Action)

## 4.1 Toute page doit contenir des CTA
Minimum recommandé :
- 1 CTA principal
- 1 CTA secondaire
- 1 CTA de relance en bas de page

---

## 4.2 Typologie des CTA DahiraLink

### CTA principal
Action centrale de la page.

#### Exemples
- Créer un khatm
- Rejoindre une session
- Commencer maintenant

### CTA secondaire
Navigation douce ou exploration.

#### Exemples
- Retour à l’accueil
- Découvrir la plateforme
- Voir comment ça fonctionne

### CTA relationnel / communautaire
Renforce l’implication.

#### Exemples
- Nous contacter
- Partager autour de soi
- Proposer une amélioration

---

## 4.3 Positionnement recommandé
Minimum par page :
- 1 CTA en haut
- 1 CTA au milieu ou après section informative
- 1 CTA en bas

### Règle
Après une section importante, toujours vérifier s’il faut relancer une action.

---

# 5. Structure standard d’une page DahiraLink

## 5.1 Structure type recommandée

```text
1. Hero
2. Sous-navigation (si page longue)
3. Bloc intention / contexte
4. Bloc valeur / fonctionnalités / explication
5. Bloc identité / ton / projet
6. CTA final